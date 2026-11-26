<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'title', 'eyebrow', 'summary', 'body'];

    public function toCatalog(): array
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'eyebrow' => $this->eyebrow,
            'summary' => $this->summary,
            'body' => $this->body,
        ];
    }
}
