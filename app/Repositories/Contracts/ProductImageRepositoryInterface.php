<?php

namespace App\Repositories\Contracts;
use Illuminate\Support\Collection;
use App\Models\ProductImage;

interface ProductImageRepositoryInterface
{
    /**
     * Create a new product image.
     *
     * @param array $data Image data (product_id, image_path, is_primary)
     * @return ProductImage
     */
    public function create(array $data): ProductImage;

    /**
     * Get an image by ID.
     *
     * @param int $id
     * @return ProductImage|null
     */
    public function findById(int $id): ?ProductImage;

    /**
     * Get all images for a product.
     *
     * @param int $productId
     * @return Collection
     */
    public function getProductImages(int $productId): Collection;

    /**
     * Get the primary image for a product.
     *
     * @param int $productId
     * @return ProductImage|null
     */
    public function getPrimaryImage(int $productId): ?ProductImage;

    /**
     * Get all secondary images for a product.
     *
     * @param int $productId
     * @return Collection
     */
    public function getSecondaryImages(int $productId): Collection;

    /**
     * Update an image.
     *
     * @param ProductImage $image
     * @param array $data
     * @return ProductImage
     */
    public function update(ProductImage $image, array $data): ProductImage;

    /**
     * Delete an image.
     *
     * @param ProductImage $image
     * @return bool
     */
    public function delete(ProductImage $image): bool;

    /**
     * Set an image as primary and unset others for the same product.
     *
     * @param ProductImage $image
     * @return bool
     */
    public function setPrimaryImage(ProductImage $image): bool;

    /**
     * Delete all images for a product.
     *
     * @param int $productId
     * @return int Number of deleted images
     */
    public function deleteProductImages(int $productId): int;

    /**
     * Check if a product has a primary image.
     *
     * @param int $productId
     * @return bool
     */
    public function hasPrimaryImage(int $productId): bool;

    /**
     * Count images for a product.
     *
     * @param int $productId
     * @return int
     */
    public function countProductImages(int $productId): int;
}
