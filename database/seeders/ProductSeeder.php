<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Support\ProductCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (ProductCatalog::all() as $data) {
            $product = Product::firstOrCreate(
                ['slug' => $data['id']],
                [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'price' => $data['price'],
                    'category' => $data['category'],
                    'is_active' => true,
                ]
            );

            if (! $product->main_image) {
                $stored = $this->downloadImage($data['image'], $product->id);

                if ($stored) {
                    $product->update(['main_image' => $stored]);
                }
            }

            if ($product->sizes()->count() === 0) {
                foreach ($data['sizes'] as $index => $sizeName) {
                    ProductSize::create([
                        'product_id' => $product->id,
                        'name' => $sizeName,
                        'sort_order' => $index,
                    ]);
                }
            }

            if ($product->colors()->count() === 0) {
                foreach ($data['colors'] as $colorIndex => $colorName) {
                    $color = ProductColor::create([
                        'product_id' => $product->id,
                        'name' => $colorName,
                        'sort_order' => $colorIndex,
                    ]);

                    if ($colorIndex === 0) {
                        foreach ($data['slides'] as $slideIndex => $slide) {
                            $stored = $this->downloadImage($slide['image'], $product->id);

                            if (! $stored) {
                                continue;
                            }

                            ProductImage::create([
                                'product_id' => $product->id,
                                'product_color_id' => $color->id,
                                'path' => $stored,
                                'title' => $slide['title'] ?? null,
                                'caption' => $slide['caption'] ?? null,
                                'sort_order' => $slideIndex,
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Download a remote image and store it on the "public" disk, returning the stored path.
     */
    private function downloadImage(string $url, int $productId): ?string
    {
        try {
            $response = Http::timeout(15)->get($url);

            if (! $response->successful()) {
                return null;
            }

            $extension = Str::of(parse_url($url, PHP_URL_PATH) ?? '')->afterLast('.')->limit(4, '')->value();
            $extension = in_array($extension, ['jpg', 'jpeg', 'png', 'webp', 'gif'], true) ? $extension : 'jpg';

            $path = "products/{$productId}/".Str::random(20).'.'.$extension;

            Storage::disk('public')->put($path, $response->body());

            return $path;
        } catch (\Throwable $e) {
            Log::warning('ProductSeeder: failed to download image', ['url' => $url, 'error' => $e->getMessage()]);

            return null;
        }
    }
}
