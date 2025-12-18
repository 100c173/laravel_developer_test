<?php

namespace App\Repositories\Eloquent;


use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository implements ProductRepositoryInterface
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query();

        if (!empty($filters['search'])) {
            $search = trim($filters['search']);

            $query->where(function ($q) use ($search) {
                $q->where('title->en', 'like', "%{$search}%")
                    ->orWhere('title->ar', 'like', "%{$search}%");
            });
        }

        return $query->latest()->paginate($perPage);
    }

    public function find(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(Product $product, array $data): Product
    {
        $product->update($data);

        return $product;
    }

    public function delete(Product $product): bool
    {
        return (bool) $product->delete();
    }

    public function addImage(Product $product, string $path): ProductImage
    {
        return $product->images()->create([
            'image_path' => $path,
        ]);
    }

    public function setPrimaryImage(ProductImage $image): void
    {
        DB::transaction(function () use ($image) {
            /**
             * Ensure only one primary image per product.
             */
            ProductImage::where('product_id', $image->product_id)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);

            $image->update(['is_primary' => true]);
        });
    }

    public function deleteImage(ProductImage $image): bool
    {
        return (bool) $image->delete();
    }
}
