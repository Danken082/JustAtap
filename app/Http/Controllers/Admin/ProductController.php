<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductImage;
use App\Models\ProductSize;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::with(['colors', 'sizes', 'images'])
            ->latest()
            ->get();

        return view('admin.products.index', [
            'products' => $products,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $product = DB::transaction(function () use ($request, $data) {
            $product = Product::create([
                'name' => $data['name'],
                'slug' => Product::makeUniqueSlug($data['name']),
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'category' => $data['category'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ]);

            if ($request->hasFile('main_image')) {
                $product->update([
                    'main_image' => $this->storeImage($request->file('main_image'), $product->id),
                ]);
            }

            foreach ($request->input('sizes', []) as $index => $sizeName) {
                $sizeName = trim((string) $sizeName);

                if ($sizeName === '') {
                    continue;
                }

                ProductSize::create([
                    'product_id' => $product->id,
                    'name' => $sizeName,
                    'sort_order' => $index,
                ]);
            }

            foreach ($request->input('colors', []) as $index => $colorData) {
                $colorName = trim((string) ($colorData['name'] ?? ''));

                if ($colorName === '') {
                    continue;
                }

                $color = ProductColor::create([
                    'product_id' => $product->id,
                    'name' => $colorName,
                    'sort_order' => $index,
                ]);

                $files = $request->file("colors.{$index}.images", []);

                foreach ($files as $imageIndex => $file) {
                    $this->createImageRecord($product->id, $color->id, $file, $imageIndex);
                }
            }

            return $product;
        });

        return redirect()->route('admin.products.index')->with('status', "Product \"{$product->name}\" created.");
    }

    public function edit(Product $product): View
    {
        $product->load(['colors.images', 'sizes', 'images' => function ($query) {
            $query->whereNull('product_color_id');
        }]);

        return view('admin.products.edit', [
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validateProduct($request, $product->id);

        DB::transaction(function () use ($request, $product, $data) {
            $updatePayload = [
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'category' => $data['category'] ?? null,
                'is_active' => $request->boolean('is_active', true),
            ];

            if ($data['name'] !== $product->name) {
                $updatePayload['slug'] = Product::makeUniqueSlug($data['name'], $product->id);
            }

            if ($request->hasFile('main_image')) {
                $updatePayload['main_image'] = $this->storeImage($request->file('main_image'), $product->id);
            }

            $product->update($updatePayload);

            // Remove selected sizes.
            ProductSize::whereIn('id', $request->input('delete_size_ids', []))
                ->where('product_id', $product->id)
                ->delete();

            // Update remaining sizes' names.
            foreach ($request->input('existing_sizes', []) as $sizeId => $sizeName) {
                $sizeName = trim((string) $sizeName);

                if ($sizeName === '') {
                    continue;
                }

                ProductSize::where('id', $sizeId)->where('product_id', $product->id)->update(['name' => $sizeName]);
            }

            // Add new sizes.
            foreach ($request->input('new_sizes', []) as $index => $sizeName) {
                $sizeName = trim((string) $sizeName);

                if ($sizeName === '') {
                    continue;
                }

                ProductSize::create([
                    'product_id' => $product->id,
                    'name' => $sizeName,
                    'sort_order' => 1000 + $index,
                ]);
            }

            // Remove selected standalone images.
            ProductImage::whereIn('id', $request->input('delete_image_ids', []))
                ->where('product_id', $product->id)
                ->get()
                ->each(function (ProductImage $image) {
                    Storage::disk('public')->delete($image->path);
                    $image->delete();
                });

            // Remove selected colors (cascades their images).
            $colorsToDelete = ProductColor::whereIn('id', $request->input('delete_color_ids', []))
                ->where('product_id', $product->id)
                ->get();

            foreach ($colorsToDelete as $color) {
                foreach ($color->images as $image) {
                    Storage::disk('public')->delete($image->path);
                }
                $color->delete();
            }

            // Update existing colors' names and append new images.
            foreach ($request->input('existing_colors', []) as $colorId => $colorData) {
                $color = ProductColor::where('id', $colorId)->where('product_id', $product->id)->first();

                if (! $color) {
                    continue;
                }

                $colorName = trim((string) ($colorData['name'] ?? ''));

                if ($colorName !== '') {
                    $color->update(['name' => $colorName]);
                }

                $files = $request->file("existing_colors.{$colorId}.images", []);

                foreach ($files as $imageIndex => $file) {
                    $this->createImageRecord($product->id, $color->id, $file, $imageIndex);
                }
            }

            // Add brand new colors.
            foreach ($request->input('new_colors', []) as $index => $colorData) {
                $colorName = trim((string) ($colorData['name'] ?? ''));

                if ($colorName === '') {
                    continue;
                }

                $color = ProductColor::create([
                    'product_id' => $product->id,
                    'name' => $colorName,
                    'sort_order' => 1000 + $index,
                ]);

                $files = $request->file("new_colors.{$index}.images", []);

                foreach ($files as $imageIndex => $file) {
                    $this->createImageRecord($product->id, $color->id, $file, $imageIndex);
                }
            }

            // Append new standalone (non-color) images.
            foreach ($request->file('new_images', []) as $imageIndex => $file) {
                $this->createImageRecord($product->id, null, $file, $imageIndex);
            }
        });

        return redirect()->route('admin.products.index')->with('status', "Product \"{$product->name}\" updated.");
    }

    public function destroy(Product $product): RedirectResponse
    {
        DB::transaction(function () use ($product) {
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->path);
            }

            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('status', "Product \"{$product->name}\" deleted.");
    }

    /**
     * @return array{name: string, description: ?string, price: float, category: ?string}
     */
    private function validateProduct(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
            'main_image' => ['nullable', 'image', 'max:4096'],
            'sizes' => ['nullable', 'array'],
            'sizes.*' => ['nullable', 'string', 'max:100'],
            'colors' => ['nullable', 'array'],
            'colors.*.name' => ['nullable', 'string', 'max:100'],
            'colors.*.images.*' => ['nullable', 'image', 'max:4096'],
            'existing_sizes' => ['nullable', 'array'],
            'existing_sizes.*' => ['nullable', 'string', 'max:100'],
            'new_sizes' => ['nullable', 'array'],
            'new_sizes.*' => ['nullable', 'string', 'max:100'],
            'delete_size_ids' => ['nullable', 'array'],
            'existing_colors' => ['nullable', 'array'],
            'existing_colors.*.name' => ['nullable', 'string', 'max:100'],
            'existing_colors.*.images.*' => ['nullable', 'image', 'max:4096'],
            'new_colors' => ['nullable', 'array'],
            'new_colors.*.name' => ['nullable', 'string', 'max:100'],
            'new_colors.*.images.*' => ['nullable', 'image', 'max:4096'],
            'delete_color_ids' => ['nullable', 'array'],
            'delete_image_ids' => ['nullable', 'array'],
            'new_images.*' => ['nullable', 'image', 'max:4096'],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }

    private function storeImage(UploadedFile $file, int $productId): string
    {
        return $file->store("products/{$productId}", 'public');
    }

    private function createImageRecord(int $productId, ?int $colorId, UploadedFile $file, int $sortOrder): ProductImage
    {
        return ProductImage::create([
            'product_id' => $productId,
            'product_color_id' => $colorId,
            'path' => $this->storeImage($file, $productId),
            'sort_order' => $sortOrder,
        ]);
    }

        public function editUserProfile(Request $request, string $cardId): View
    {
        $user = User::where('card_id', $cardId)->firstOrFail();
        $profile = $this->profileForUser($user)->load('links');

        return view('admin.profile.edituserprofile', [
            'user' => $user,
            'profile' => $profile,
            'linkTypes' => $this->linkTypes(),
        ]);
    }
}
