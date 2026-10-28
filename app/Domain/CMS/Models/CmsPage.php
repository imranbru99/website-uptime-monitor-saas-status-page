<?php

namespace App\Domain\CMS\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ImranDev\UniversalSlug\Facades\UniversalSlug;

class CmsPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'summary',
        'content',
        'layout',
        'meta_title',
        'meta_description',
        'is_published',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (CmsPage $page) {
            if (empty($page->slug)) {
                $page->slug = UniversalSlug::generate($page->title);
            }
        });
    }
}
