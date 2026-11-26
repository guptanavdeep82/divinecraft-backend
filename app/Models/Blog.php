<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'body', 'image', 'author', 'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function toCatalog(bool $withBody = false): array
    {
        $payload = [
            'slug' => $this->slug,
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'image' => $this->image,
            'author' => $this->author,
            'published_label' => $this->published_at?->format('d M Y'),
        ];

        if ($withBody) {
            $payload['body'] = $this->body;
        }

        return $payload;
    }
};
