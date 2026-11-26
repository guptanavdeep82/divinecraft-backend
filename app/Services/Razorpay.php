<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class Razorpay
{
    public function key(): ?string
    {
        return Setting::get('razorpay_key_id') ?: config('services.razorpay.key');
    }

    public function secret(): ?string
    {
        return Setting::get('razorpay_key_secret') ?: config('services.razorpay.secret');
    }

    public function ready(): bool
    {
        return filled($this->key()) && filled($this->secret());
    }

    public function createOrder(int $amountPaise, string $receipt): array
    {
        if (! $this->ready()) {
            throw new RuntimeException('Add the Razorpay key and secret in Admin → Settings.');
        }

        $response = Http::withBasicAuth($this->key(), $this->secret())
            ->acceptJson()
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => $amountPaise,
                'currency' => 'INR',
                'receipt' => $receipt,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException($response->json('error.description') ?? 'Razorpay could not open this order.');
        }

        return $response->json();
    }

    public function verify(string $orderId, string $paymentId, string $signature): bool
    {
        $secret = $this->secret();
        if (! $secret) {
            return false;
        }

        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $secret);

        return hash_equals($expected, $signature);
    }
}
