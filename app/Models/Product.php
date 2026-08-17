<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'category',
        'main_image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class)->orderBy('sort_order');
    }

    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class)->orderBy('sort_order');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public static function makeUniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $suffix = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.(++$suffix);
        }

        return $slug;
    }

    public function mainImageUrl(): ?string
    {
        if ($this->main_image) {
            return $this->resolveUrl($this->main_image);
        }

        $firstImage = $this->images->first() ?? $this->images()->first();

        return $firstImage ? $this->resolveUrl($firstImage->path) : null;
    }

    public function resolveUrl(string $path): string
    {
        return Str::startsWith($path, ['http://', 'https://'])
            ? $path
            : Storage::disk('public')->url($path);
    }

    /**
     * Build the array shape used by the storefront/cart (colors, sizes, slides).
     *
     * @return array<string, mixed>
     */
    public function toCatalogArray(): array
    {
        $colors = $this->colors->pluck('name')->values()->all();
        $sizes = $this->sizes->pluck('name')->values()->all();

        $slides = $this->images->map(function (ProductImage $image) {
            return [
                'title' => $image->title ?: $this->name,
                'caption' => $image->caption ?: $this->description,
                'image' => $this->resolveUrl($image->path),
            ];
        })->values()->all();

        $imagesByColor = $this->images->groupBy('product_color_id');

        $colorImages = [];

        foreach ($this->colors as $color) {
            $colorImages[$color->name] = $imagesByColor->get($color->id, collect())
                ->map(function (ProductImage $image) {
                    return [
                        'title' => $image->title ?: $this->name,
                        'caption' => $image->caption ?: $this->description,
                        'image' => $this->resolveUrl($image->path),
                    ];
                })->values()->all();
        }

        return [
            'id' => (string) $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'image' => $this->mainImageUrl(),
            'category' => $this->category,
            'colors' => $colors ?: ['Standard'],
            'sizes' => $sizes ?: ['Standard'],
            'slides' => $slides,
            'color_images' => $colorImages,
        ];
    }
}
