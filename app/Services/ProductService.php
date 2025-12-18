<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * Create product (images are optional).
     */
    public function create(array $data): Product
    {
        return $this->productRepository->create($data);
    }

    /**
     * Update product data.
     */
    public function update(Product $product, array $data): Product
    {
        return $this->productRepository->update($product, $data);
    }
       /**
     * Add multiple images to product gallery.
     *
     * This method:
     * - Supports bulk image upload
     * - Ensures atomicity using database transactions
     * - Does NOT handle primary image logic
     *
     * @param Product $product
     * @param array   $data   Validated data from StoreImagesRequest
     *
     * @return void
     */
    public function addImage(Product $product, array $data): void
    {
        DB::transaction(function () use ($product, $data) {

            /**
             * Iterate through validated images array.
             * Each image is guaranteed to be an instance of UploadedFile.
             */
            foreach ($data['images'] as $image) {

                /**
                 * Store image securely on the public disk.
                 * Path is stored in database, not the file itself.
                 */
                $path = $image->store(
                    'products',
                    'public'
                );

                /**
                 * Persist gallery image record.
                 * All images are non-primary by default.
                 */
                $this->productRepository->addImage(
                    $product,
                    $path,
                  
                );
            }
        });
    }

    /**
     * Set an existing image as primary.
     */
    public function setPrimaryImage(ProductImage $image): void
    {
        $this->productRepository->setPrimaryImage($image);
    }

    /**
     * Delete product image (file + record).
     */
    public function deleteImage(ProductImage $image): bool
    {
        Storage::disk('public')->delete($image->image_path);

        return $this->productRepository->deleteImage($image);
    }

    /**
     * Store image securely.
     */
    protected function storeImage(UploadedFile $image): string
    {
        return $image->store('products', 'public');
    }

}

