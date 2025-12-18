<?php

namespace App\Repositories\Contracts;


use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Pagination\LengthAwarePaginator;


interface ProductRepositoryInterface
{
    /**
     * Paginate products with optional filters.
     */
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a product by ID.
     */
    public function find(int $id): Product;

    /**
     * Create a new product.
     */
    public function create(array $data): Product;

    /**
     * Update an existing product.
     */
    public function update(Product $product, array $data): Product;

    /**
     * Soft delete a product.
     */
    public function delete(Product $product): bool;

    /**
     * Store product image record.
     */
    public function addImage(Product $product,string $path): ProductImage;

    /**
     * Mark a specific image as primary.
     * Ensures only one primary image exists per product.
     */
    public function setPrimaryImage(ProductImage $image): void;

    /**
     * Delete a product image.
     */
    public function deleteImage(ProductImage $image): bool;
}
