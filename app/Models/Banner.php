<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'eyebrow', 'title', 'subtitle', 'cta', 'cta_href', 'cta2', 'note',
        'image', 'image_alt', 'image2', 'chip_top', 'chip', 'background', 'sort', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function toCatalog(): array
    {
        return [
            'eyebrow' => $this->eyebrow,
            'title' => $this->title,
            'sub' => $this->subtitle,
            'cta' => $this->cta,
            'href' => $this->cta_href,
            'cta2' => $this->cta2,
            'note' => $this->note,
            'img' => $this->image,
            'img2' => $this->image2,
            'alt' => $this->image_alt ?: $this->title,
            'chipTop' => $this->chip_top,
            'chip' => $this->chip,
            'bg' => $this->background,
        ];
    }
}
