<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductGroup extends Model
{
    public $timestamps = false;

    protected $fillable = ['product_id', 'group', 'sort'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
