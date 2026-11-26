<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['name', 'city', 'quote', 'rating', 'sort', 'is_active'];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
            'sort' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function toCatalog(): array
    {
        return [
            'name' => $this->name,
            'city' => $this->city,
            'quote' => $this->quote,
            'rating' => $this->rating,
        ];
    }
}
