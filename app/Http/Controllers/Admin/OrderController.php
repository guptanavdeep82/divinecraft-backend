<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\Shiprocket;
use Illuminate\Http\Request;
use RuntimeException;

class OrderController extends Controller
{
    public function orders(Request $request)
    {
        $status = $request->query('status');
        $orders = Order::query()
            ->with(['product', 'items'])
            ->when(in_array($status, ['pending', 'paid', 'failed'], true), fn ($query) => $query->where('status', $status))
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(Order $order)
    {
        $order->load(['product', 'payments', 'items']);

        return view('admin.orders.show', compact('order'));
    }

    public function ship(Order $order, Shiprocket $shiprocket)
    {
        if ($order->status !== 'paid') {
            return back()->with('status', 'Book the parcel after the payment is captured.');
        }

        try {
            $shiprocket->book($order);

            return back()->with('status', 'Shiprocket has the order. AWB appears once a courier is assigned.');
        } catch (RuntimeException $exception) {
            return back()->with('status', $exception->getMessage());
        }
    }

    public function refresh(Order $order, Shiprocket $shiprocket)
    {
        try {
            $shiprocket->refresh($order);

            return back()->with('status', 'Tracking updated.');
        } catch (RuntimeException $exception) {
            return back()->with('status', $exception->getMessage());
        }
    }

    public function payments()
    {
        $payments = Payment::query()->with('order')->latest()->get();

        return view('admin.payments.index', compact('payments'));
    }

    public function report(Request $request)
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to = $request->date('to') ?? now();
        $start = $from->copy()->startOfDay();
        $end = $to->copy()->endOfDay();

        $paid = Order::query()->where('status', 'paid')->whereBetween('paid_at', [$start, $end]);
        $orders = Order::query()->whereBetween('created_at', [$start, $end]);

        return view('admin.reports.index', [
            'from' => $start->toDateString(),
            'to' => $end->toDateString(),
            'revenue' => (clone $paid)->sum('amount'),
            'paidCount' => (clone $paid)->count(),
            'pending' => (clone $orders)->where('status', 'pending')->count(),
            'failed' => (clone $orders)->where('status', 'failed')->count(),
            'attempts' => Payment::query()->whereBetween('created_at', [$start, $end])->count(),
            'captured' => Payment::query()->where('status', 'captured')->whereBetween('updated_at', [$start, $end])->count(),
            'recent' => Order::query()->with('product')->whereBetween('created_at', [$start, $end])->latest()->limit(20)->get(),
        ]);
    }
}
