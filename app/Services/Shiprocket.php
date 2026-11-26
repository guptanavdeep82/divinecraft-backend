<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class Shiprocket
{
    private const BASE = 'https://apiv2.shiprocket.in/v1/external';

    public function email(): ?string
    {
        return Setting::get('shiprocket_email') ?: config('services.shiprocket.email');
    }

    public function password(): ?string
    {
        return Setting::get('shiprocket_password') ?: config('services.shiprocket.password');
    }

    public function ready(): bool
    {
        return filled($this->email()) && filled($this->password());
    }

    public function book(Order $order): Order
    {
        if ($order->shiprocket_order_id) {
            $this->refresh($order);

            return $order->fresh();
        }

        if (! $order->address || ! $order->pincode) {
            throw new RuntimeException('Add a delivery address before booking Shiprocket.');
        }

        $response = $this->send('post', '/orders/create/adhoc', $this->payload($order));
        if (! $response->successful()) {
            throw new RuntimeException($this->message($response));
        }

        $json = $response->json();
        $order->update([
            'shiprocket_order_id' => $json['order_id'] ?? null,
            'shiprocket_shipment_id' => $json['shipment_id'] ?? null,
            'awb' => filled($json['awb_code'] ?? null) ? $json['awb_code'] : null,
            'courier' => filled($json['courier_name'] ?? null) ? $json['courier_name'] : null,
            'tracking_status' => $json['status'] ?? 'Booked',
        ]);

        $this->assignCourier($order->fresh());

        return $order->fresh();
    }

    public function refresh(Order $order): array
    {
        $snapshot = $this->snapshot($order);
        $order->update([
            'awb' => $snapshot['awb'] ?: $order->awb,
            'courier' => $snapshot['courier'] ?: $order->courier,
            'tracking_status' => $snapshot['current_status'] ?: $order->tracking_status,
        ]);

        return $snapshot;
    }

    public function snapshot(Order $order): array
    {
        $order->loadMissing('product');
        $base = [
            'order_number' => $order->number,
            'status' => $order->status,
            'amount' => $order->rupees(),
            'product' => $order->product?->name,
            'awb' => $order->awb,
            'courier' => $order->courier,
            'current_status' => $order->tracking_status,
            'events' => [],
            'message' => null,
        ];

        if (! $order->awb && ! $order->shiprocket_shipment_id && ! $order->shiprocket_order_id) {
            $base['message'] = $order->tracking_status
                ?: ($order->status === 'paid'
                    ? 'Payment is in. The parcel has not been booked with the courier yet.'
                    : 'This order is not paid yet, so there is no shipment to track.');
            $base['current_status'] = null;

            return $base;
        }

        if (! $this->ready()) {
            $base['message'] = 'The parcel is booked in the studio. Live courier scans appear once Shiprocket is connected.';

            return $base;
        }

        try {
            $json = $this->pull($order);
        } catch (RuntimeException $exception) {
            $base['message'] = $exception->getMessage();

            return $base;
        }

        $data = $json['tracking_data'] ?? $json;
        $track = $data['shipment_track'][0] ?? [];
        $activities = $data['shipment_track_activities'] ?? [];

        $base['awb'] = $track['awb_code'] ?? $order->awb;
        $base['courier'] = $track['courier_name'] ?? $order->courier;
        $base['current_status'] = $track['current_status'] ?? $order->tracking_status;
        $base['events'] = collect($activities)->map(fn ($row) => [
            'date' => $row['date'] ?? '',
            'activity' => $row['activity'] ?? ($row['status'] ?? ''),
            'location' => $row['location'] ?? '',
        ])->filter(fn ($row) => $row['activity'] !== '')->values()->all();
        $base['message'] = $base['events'] === []
            ? 'Courier has not scanned this parcel yet.'
            : null;

        return $base;
    }

    private function assignCourier(Order $order): void
    {
        $pickup = Setting::get('shiprocket_pickup_pincode') ?: config('services.shiprocket.pickup_pincode');
        if (! $order->shiprocket_shipment_id || ! filled($pickup) || $order->awb) {
            return;
        }

        $service = $this->send('get', '/courier/serviceability/', [
            'pickup_postcode' => $pickup,
            'delivery_postcode' => $order->pincode,
            'weight' => 0.4,
            'cod' => 0,
        ]);

        $couriers = $service->json('data.available_courier_companies') ?? [];
        $chosen = collect($couriers)->sortBy('rate')->first();
        if (! $chosen) {
            $order->update(['tracking_status' => 'Booked. No courier is available for this pincode yet.']);

            return;
        }

        $assigned = $this->send('post', '/courier/assign/awb', [
            'shipment_id' => $order->shiprocket_shipment_id,
            'courier_id' => $chosen['courier_company_id'],
        ]);

        $awb = $assigned->json('response.data.awb_code');
        if ($assigned->successful() && filled($awb)) {
            $order->update([
                'awb' => $awb,
                'courier' => $assigned->json('response.data.courier_name') ?: ($chosen['courier_name'] ?? null),
                'tracking_status' => 'AWB assigned',
            ]);

            return;
        }

        $order->update(['tracking_status' => 'Booked. '.$this->message($assigned)]);
    }

    private function pull(Order $order): array
    {
        if ($order->awb) {
            $response = $this->send('get', '/courier/track/awb/'.$order->awb);
        } elseif ($order->shiprocket_shipment_id) {
            $response = $this->send('get', '/courier/track/shipment/'.$order->shiprocket_shipment_id);
        } else {
            $response = $this->send('get', '/courier/track', ['order_id' => $order->number]);
        }

        if (! $response->successful()) {
            throw new RuntimeException($this->message($response));
        }

        return $response->json();
    }

    private function payload(Order $order): array
    {
        $order->loadMissing('product');
        $parts = preg_split('/\s+/', trim($order->customer_name), 2) ?: [$order->customer_name];
        $phone = substr(preg_replace('/\D/', '', $order->phone) ?? '', -10);

        return [
            'order_id' => $order->number,
            'order_date' => $order->created_at->format('Y-m-d H:i'),
            'pickup_location' => Setting::get('shiprocket_pickup') ?: config('services.shiprocket.pickup') ?: 'Primary',
            'billing_customer_name' => $parts[0],
            'billing_last_name' => $parts[1] ?? '.',
            'billing_address' => $order->address,
            'billing_city' => $order->city,
            'billing_pincode' => $order->pincode,
            'billing_state' => $order->state,
            'billing_country' => 'India',
            'billing_email' => $order->email ?: 'seva@divinekraft.com',
            'billing_phone' => $phone,
            'shipping_is_billing' => true,
            'order_items' => [[
                'name' => $order->product?->name ?? 'Poshak',
                'sku' => $order->product?->slug ?? $order->number,
                'units' => 1,
                'selling_price' => $order->amount,
            ]],
            'payment_method' => 'Prepaid',
            'sub_total' => $order->amount,
            'length' => 25,
            'breadth' => 20,
            'height' => 6,
            'weight' => 0.4,
        ];
    }

    private function token(bool $fresh = false): string
    {
        if (! $this->ready()) {
            throw new RuntimeException('Add the Shiprocket email and password in Admin → Settings.');
        }

        if (! $fresh && Cache::has('shiprocket.token')) {
            return Cache::get('shiprocket.token');
        }

        $response = Http::acceptJson()->post(self::BASE.'/auth/login', [
            'email' => $this->email(),
            'password' => $this->password(),
        ]);

        $token = $response->json('token');
        if (! $response->successful() || ! filled($token)) {
            throw new RuntimeException($this->message($response));
        }

        Cache::put('shiprocket.token', $token, now()->addDays(9));

        return $token;
    }

    private function send(string $method, string $path, array $data = []): Response
    {
        $request = function () use ($method, $path, $data) {
            $pending = Http::withToken($this->token())->acceptJson();

            return $method === 'get'
                ? $pending->get(self::BASE.$path, $data)
                : $pending->post(self::BASE.$path, $data);
        };

        $response = $request();
        if ($response->status() === 401) {
            Cache::forget('shiprocket.token');
            $this->token(true);
            $response = $request();
        }

        return $response;
    }

    private function message(Response $response): string
    {
        $message = $response->json('message') ?? $response->json('error') ?? 'Shiprocket could not complete that request.';
        if (is_array($message)) {
            $message = collect($message)->flatten()->filter()->first() ?: 'Shiprocket could not complete that request.';
        }

        return (string) $message;
    }
};
