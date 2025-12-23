<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductImage;
use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Http\UploadedFile;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Str;


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
                //Special storage (raw)
                $privatePath = $this->storePrivate($product, $image);

                //Process + Copy to public
                $publicPath = $this->publishImage($product, $privatePath);

                // Save the general path only
                $this->productRepository->addImage(
                    $product,
                    $publicPath
                );

                Storage::disk('local')->delete($privatePath);
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

  
    protected function publishImage(Product $product, string $privatePath): string
    {
        $filename = Str::uuid() . '.jpg';

        $publicPath = 'products/' . $product->id . '/' . $filename;

        Storage::disk('public')->put(
            $publicPath,
            Storage::disk('local')->get($privatePath)
        );

        return $publicPath;
    }

    protected function storePrivate(Product $product, UploadedFile $image): string
    {
        return $image->store(
            'products/raw/' . $product->id,
            'local'
        );
    }


}

