<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
class ProductController extends Controller
{
     public function __construct(protected ProductService $productService) {}

    /**
     * List products (paginated)
     */
    public function index(Request $request): JsonResponse
    {
        $products = Product::paginate($request->get('per_page', 15));

        return self::paginated($products, 'products.listed_successfully');
    }

    /**
     * Show product
     */
    public function show(int $id): JsonResponse
    {
        $product = Product::with('images')->find($id);

        if (!$product) {
            return self::error('products.not_found', 404);
        }

        return self::success($product, 'products.fetched_successfully');
    }

    /**
     * Create product
     */
    public function store(Request $request): JsonResponse
    {
        $product = $this->productService->create($request->all());

        return self::success($product, 'products.created_successfully', 201);
    }

    /**
     * Update product
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return self::error('products.not_found', 404);
        }

        $updatedProduct = $this->productService->update($product, $request->all());

        return self::success($updatedProduct, 'products.updated_successfully');
    }

    /**
     * Delete product
     */
    public function destroy(int $id): JsonResponse
    {
        $product = Product::find($id);

        if (!$product) {
            return self::error('products.not_found', 404);
        }

        $product->delete();

        return self::success(null, 'products.deleted_successfully');
    }

    /**
     * Assign product to user
     */
    public function assignToUser(Request $request, int $productId): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $product = Product::find($productId);
        $user = User::find($request->user_id);

        if (!$product || !$user) {
            return self::error('resources.not_found', 404);
        }

        // minimal logic: assign product to user
        $product->user()->associate($user);
        $product->save();

        return self::success($product->fresh(), 'products.assigned_successfully');
    }
}
