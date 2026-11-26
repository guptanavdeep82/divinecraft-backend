<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'number', 'product_id', 'user_id', 'customer_name', 'phone', 'email',
        'address', 'city', 'state', 'pincode', 'personalise',
        'embroidery_name', 'script', 'zari', 'note', 'amount', 'currency', 'status',
        'razorpay_order_id', 'paid_at', 'shiprocket_order_id', 'shiprocket_shipment_id',
        'awb', 'courier', 'tracking_status',
    ];

    protected function casts(): array
    {
        return [
            'personalise' => 'boolean',
            'amount' => 'integer',
            'paid_at' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function poshakLabel(): string
    {
        $items = $this->relationLoaded('items') ? $this->items : $this->items()->get();
        if ($items->count() > 1) {
            return $items->count().' poshaks';
        }
        if ($item = $items->first()) {
            return $item->qty > 1 ? $item->name.' × '.$item->qty : $item->name;
        }

        return $this->product?->name ?? '—';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function matchesPhone(string $phone): bool
    {
        $left = substr(preg_replace('/\D/', '', $this->phone) ?? '', -10);
        $right = substr(preg_replace('/\D/', '', $phone) ?? '', -10);

        return $left !== '' && $left === $right;
    }

    public function toAccount(): array
    {
        return [
            'order_number' => $this->number,
            'product' => $this->poshakLabel(),
            'amount' => $this->rupees(),
            'status' => $this->status,
            'tracking_status' => $this->tracking_status,
            'awb' => $this->awb,
            'courier' => $this->courier,
            'placed' => $this->created_at?->format('d M Y'),
        ];
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function rupees(): string
    {
        return '₹'.number_format($this->amount);
    }
}
