<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'phone' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'password' => $data['password'],
            'role' => 'customer',
        ]);

        $this->claimOrders($user);

        return response()->json([
            'token' => $this->issue($user),
            'user' => $user->profile(),
        ], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::query()->where('email', $data['email'])->where('role', 'customer')->first();
        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Those details do not match an account.'], 422);
        }

        $this->claimOrders($user);

        return response()->json([
            'token' => $this->issue($user),
            'user' => $user->profile(),
        ]);
    }

    public function me(Request $request)
    {
        return response()->json($request->user()->profile());
    }

    public function logout(Request $request)
    {
        $plain = $request->bearerToken();
        if ($plain) {
            $request->user()->tokens()->where('token', hash('sha256', $plain))->delete();
        }

        return response()->json(['ok' => true]);
    }

    public function orders(Request $request)
    {
        $orders = Order::query()
            ->with('product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get()
            ->map(fn (Order $order) => $order->toAccount());

        return response()->json($orders);
    }

    private function issue(User $user): string
    {
        $plain = Str::random(48);
        $user->tokens()->create(['token' => hash('sha256', $plain)]);

        return $plain;
    }

    private function claimOrders(User $user): void
    {
        if (! $user->phone) {
            return;
        }

        Order::query()->whereNull('user_id')->orderBy('id')->each(function (Order $order) use ($user) {
            if ($order->matchesPhone($user->phone)) {
                $order->update(['user_id' => $user->id]);
            }
        });
    }
}
