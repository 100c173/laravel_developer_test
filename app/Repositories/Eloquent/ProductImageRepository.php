<?php

namespace App\Repositories\Eloquent;

use App\Models\ProductImage;
use Illuminate\Support\Collection;
use App\Repositories\Contracts\ProductImageRepositoryInterface;

class ProductImageRepository implements ProductImageRepositoryInterface
{
    /**
     * Create a new product image.
     *
     * @param array $data
     * @return ProductImage
     */
    public function create(array $data): ProductImage
    {
        return ProductImage::create($data);
    }

    /**
     * Get an image by ID.
     *
     * @param int $id
     * @return ProductImage|null
     */
    public function findById(int $id): ?ProductImage
    {
        return ProductImage::find($id);
    }

    /**
     * Get all images for a product.
     *
     * @param int $productId
     * @return Collection
     */
    public function getProductImages(int $productId): Collection
    {
        return ProductImage::where('product_id', $productId)
            ->orderBy('is_primary', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get the primary image for a product.
     *
     * @param int $productId
     * @return ProductImage|null
     */
    public function getPrimaryImage(int $productId): ?ProductImage
    {
        return ProductImage::where('product_id', $productId)
            ->where('is_primary', true)
            ->first();
    }

    /**
     * Get all secondary images for a product.
     *
     * @param int $productId
     * @return Collection
     */
    public function getSecondaryImages(int $productId): Collection
    {
        return ProductImage::where('product_id', $productId)
            ->where('is_primary', false)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Update an image.
     *
     * @param ProductImage $image
     * @param array $data
     * @return ProductImage
     */
    public function update(ProductImage $image, array $data): ProductImage
    {
        $image->update($data);
        return $image->fresh();
    }

    /**
     * Delete an image.
     *
     * @param ProductImage $image
     * @return bool
     */
    public function delete(ProductImage $image): bool
    {
        return $image->delete();
    }

    /**
     * Set an image as primary and unset others for the same product.
     * 
     * This ensures only one image per product is marked as primary.
     *
     * @param ProductImage $image
     * @return bool
     */
    public function setPrimaryImage(ProductImage $image): bool
    {
        // Start a database transaction to ensure atomicity
        \DB::beginTransaction();

        try {
            // Unset all other images as primary for this product
            ProductImage::where('product_id', $image->product_id)
                ->where('id', '!=', $image->id)
                ->update(['is_primary' => false]);

            // Set this image as primary
            $image->update(['is_primary' => true]);

            \DB::commit();
            return true;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error setting primary image: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Delete all images for a product.
     *
     * @param int $productId
     * @return int Number of deleted images
     */
    public function deleteProductImages(int $productId): int
    {
        return ProductImage::where('product_id', $productId)->delete();
    }

    /**
     * Check if a product has a primary image.
     *
     * @param int $productId
     * @return bool
     */
    public function hasPrimaryImage(int $productId): bool
    {
        return ProductImage::where('product_id', $productId)
            ->where('is_primary', true)
            ->exists();
    }

    /**
     * Count images for a product.
     *
     * @param int $productId
     * @return int
     */
    public function countProductImages(int $productId): int
    {
        return ProductImage::where('product_id', $productId)->count();
    }
}