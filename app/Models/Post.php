<?php

namespace App\Models;

use App\Models\Concerns\HasSlug;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasSlug;

    public const STATUSES = [
        'published' => 'Terbit',
        'draft' => 'Draf',
    ];

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'thumbnail', 'status', 'published_at', 'views',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '<=', now()));
    }

    public function scopeLatestPublished($query)
    {
        return $query->orderByDesc('published_at')->orderByDesc('id');
    }

    public function scopeInCategory($query, string $slug)
    {
        return $query->whereHas('categories', fn ($q) => $q->where('slug', $slug));
    }

    public function getExcerptAttribute(): string
    {
        $text = html_entity_decode(strip_tags((string) $this->content), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return Str::limit(trim(preg_replace('/\s+/u', ' ', $text)), 150);
    }

    /**
     * ID video YouTube pertama yang disematkan di konten (jika ada).
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if (preg_match('~(?:youtube(?:-nocookie)?\.com/(?:embed/|watch\?v=|shorts/)|youtu\.be/)([A-Za-z0-9_-]{11})~', (string) $this->content, $m)) {
            return $m[1];
        }

        return null;
    }

    public function getHasVideoAttribute(): bool
    {
        return (bool) preg_match('~<(iframe|video)\b~i', (string) $this->content);
    }

    /**
     * Gambar sampul: thumbnail → gambar pertama di konten → thumbnail YouTube.
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if ($this->thumbnail) {
            return media_url($this->thumbnail);
        }

        if (preg_match('~<img[^>]+src=["\']([^"\']+)["\']~i', (string) $this->content, $m)) {
            return $m[1];
        }

        if ($id = $this->youtube_id) {
            return "https://img.youtube.com/vi/{$id}/hqdefault.jpg";
        }

        return null;
    }

    public function getDateLabelAttribute(): string
    {
        return ($this->published_at ?? $this->created_at)?->translatedFormat('d F Y') ?? '';
    }
}
