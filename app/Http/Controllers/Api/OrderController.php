<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ApiToken;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Services\Razorpay;
use App\Services\Shiprocket;
use Illuminate\Http\Request;
use RuntimeException;

class OrderController extends Controller
{
    public function store(Request $request, Razorpay $razorpay)
    {
        $data = $request->validate([
            'product_slug' => ['required_without:items', 'nullable', 'string', 'max:160'],
            'items' => ['required_without:product_slug', 'array', 'min:1'],
            'items.*.slug' => ['required', 'string', 'max:160'],
            'items.*.qty' => ['nullable', 'integer', 'min:1', 'max:6'],
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:160'],
            'address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', 'max:80'],
            'pincode' => ['required', 'digits:6'],
            'message' => ['nullable', 'string', 'max:2000'],
            'personalise' => ['nullable', 'boolean'],
            'embroidery_name' => ['nullable', 'string', 'max:80'],
            'script' => ['nullable', 'string', 'max:40'],
            'zari' => ['nullable', 'string', 'max:40'],
        ]);

        $lines = collect($data['items'] ?? [['slug' => $data['product_slug'], 'qty' => 1]])
            ->map(function ($line) {
                $product = Product::query()->where('slug', $line['slug'])->where('is_active', true)->first();
                if (! $product) {
                    return null;
                }

                return ['product' => $product, 'qty' => max(1, (int) ($line['qty'] ?? 1))];
            })
            ->filter()
            ->values();

        if ($lines->isEmpty()) {
            return response()->json(['message' => 'Choose a poshak that is still available.'], 422);
        }

        $amount = $lines->sum(fn ($line) => $line['product']->price * $line['qty']);
        $description = $lines->map(fn ($line) => $line['qty'] > 1 ? $line['product']->name.' × '.$line['qty'] : $line['product']->name)->join(', ');

        $order = Order::query()->create([
            'number' => 'DK-'.str()->upper(str()->random(8)),
            'product_id' => $lines->first()['product']->id,
            'user_id' => $this->customerId($request),
            'customer_name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'address' => $data['address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'personalise' => (bool) ($data['personalise'] ?? false),
            'embroidery_name' => $data['embroidery_name'] ?? null,
            'script' => $data['script'] ?? null,
            'zari' => $data['zari'] ?? null,
            'note' => $data['message'] ?? null,
            'amount' => $amount,
            'currency' => 'INR',
            'status' => 'pending',
        ]);
        $order->update(['number' => 'DK-'.str_pad((string) $order->id, 5, '0', STR_PAD_LEFT)]);
        foreach ($lines as $line) {
            $order->items()->create([
                'product_id' => $line['product']->id,
                'name' => $line['product']->name,
                'qty' => $line['qty'],
                'price' => $line['product']->price,
            ]);
        }

        try {
            $remote = $razorpay->createOrder($order->amount * 100, $order->number);
        } catch (RuntimeException $exception) {
            $order->update(['status' => 'failed']);
            Payment::query()->create([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'status' => 'failed',
                'error' => $exception->getMessage(),
            ]);

            return response()->json(['message' => $exception->getMessage()], 422);
        }

        $order->update(['razorpay_order_id' => $remote['id']]);
        Payment::query()->create([
            'order_id' => $order->id,
            'razorpay_order_id' => $remote['id'],
            'amount' => $order->amount,
            'status' => 'created',
        ]);

        return response()->json([
            'order_number' => $order->number,
            'amount' => $order->amount,
            'amount_paise' => $order->amount * 100,
            'currency' => 'INR',
            'key_id' => $razorpay->key(),
            'razorpay_order_id' => $remote['id'],
            'name' => 'DivineKraft',
            'description' => $description,
            'customer_name' => $order->customer_name,
            'phone' => $order->phone,
        ]);
    }

    public function verify(Request $request, Razorpay $razorpay)
    {
        $data = $request->validate([
            'order_number' => ['required', 'string'],
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $order = Order::query()
            ->where('number', $data['order_number'])
            ->where('razorpay_order_id', $data['razorpay_order_id'])
            ->firstOrFail();

        if ($order->status === 'paid') {
            return response()->json(['ok' => true, 'order_number' => $order->number, 'amount' => $order->rupees()]);
        }

        $payment = $order->payments()->where('razorpay_order_id', $data['razorpay_order_id'])->latest()->first();
        if (! $payment) {
            $payment = $order->payments()->create([
                'razorpay_order_id' => $data['razorpay_order_id'],
                'amount' => $order->amount,
                'status' => 'created',
            ]);
        }

        if (! $razorpay->verify($data['razorpay_order_id'], $data['razorpay_payment_id'], $data['razorpay_signature'])) {
            $payment->update([
                'razorpay_payment_id' => $data['razorpay_payment_id'],
                'status' => 'failed',
                'error' => 'Signature did not match.',
            ]);

            return response()->json(['message' => 'Payment could not be verified.'], 422);
        }

        $payment->update([
            'razorpay_payment_id' => $data['razorpay_payment_id'],
            'razorpay_signature' => $data['razorpay_signature'],
            'status' => 'captured',
            'error' => null,
        ]);
        $order->update(['status' => 'paid', 'paid_at' => now()]);
        $this->bookShipment($order->fresh(), app(Shiprocket::class));

        return response()->json([
            'ok' => true,
            'order_number' => $order->number,
            'amount' => $order->rupees(),
        ]);
    }

    public function track(Request $request, Shiprocket $shiprocket)
    {
        $data = $request->validate([
            'order_number' => ['required', 'string', 'max:40'],
            'phone' => ['required', 'string', 'max:30'],
        ]);

        $order = Order::query()->where('number', $data['order_number'])->first();
        if (! $order || ! $order->matchesPhone($data['phone'])) {
            return response()->json(['message' => 'No order matches that number and phone.'], 404);
        }

        return response()->json($shiprocket->snapshot($order));
    }

    private function customerId(Request $request): ?int
    {
        $plain = $request->bearerToken();
        if (! $plain) {
            return null;
        }

        $token = ApiToken::query()->with('user')->where('token', hash('sha256', $plain))->first();
        if (! $token?->user || $token->user->role === 'admin') {
            return null;
        }

        return $token->user->id;
    }

    private function bookShipment(Order $order, Shiprocket $shiprocket): void
    {
        if (! $shiprocket->ready()) {
            $order->update(['tracking_status' => 'Paid. Parcel booking starts once Shiprocket is connected.']);

            return;
        }

        try {
            $shiprocket->book($order);
        } catch (RuntimeException $exception) {
            $order->update(['tracking_status' => $exception->getMessage()]);
        }
    }
}
