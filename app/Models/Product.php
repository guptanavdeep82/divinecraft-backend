<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'price', 'tier', 'badge', 'badge_color',
        'eta', 'image', 'description', 'deity', 'colour', 'is_active', 'sort',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'integer',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function groups(): HasMany
    {
        return $this->hasMany(ProductGroup::class);
    }

    public function toCatalog(): array
    {
        return [
            'slug' => $this->slug,
            'name' => $this->name,
            'price' => $this->price,
            'price_label' => '₹'.number_format($this->price),
            'tier' => $this->tier,
            'badge' => $this->badge,
            'badge_color' => $this->badge_color,
            'eta' => $this->eta,
            'image' => $this->image,
            'description' => $this->description,
            'deity' => $this->deity,
            'colour' => $this->colour,
            'category' => $this->category?->name,
        ];
    }
}
