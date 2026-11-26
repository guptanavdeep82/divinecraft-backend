<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['label', 'title', 'poster', 'video', 'href', 'sort', 'is_active'];

    protected function casts(): array
    {
        return [
            'sort' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function toCatalog(): array
    {
        return [
            'label' => $this->label,
            'title' => $this->title,
            'poster' => $this->poster,
            'video' => $this->video,
            'href' => $this->href,
        ];
    }
}
