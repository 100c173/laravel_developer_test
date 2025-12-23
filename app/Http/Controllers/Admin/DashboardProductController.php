<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreImageRequest;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardProductController extends Controller
{
    /**
     * Inject ProductService.
     *
     * Using constructor injection improves testability
     * and enforces dependency inversion.
     */
    public function __construct(protected ProductService $productService)
    {
    }

    /**
     * Display products listing page.
     */
    public function index()
    {
        return view('dashboard.products.index');
    }

    /**
     * Return DataTables JSON response.
     *
     * This method is Web-only and should never be reused
     * by API controllers.
     */
    public function datatable(Request $request)
    {
        $query = Product::query();

        return DataTables::of($query)
            ->addColumn('title', function (Product $product) {
                return $product->title;
            })
            ->addColumn('primary_image', function (Product $product) {
                $image = $product->images()
                    ->where('is_primary', true)
                    ->first();

                if ($image) {
                    return '<div class="flex justify-center">
                    <img src=" ' . asset('storage/'.$image->image_path) . '" 
                         alt="' . htmlspecialchars($product->title, ENT_QUOTES) . '" 
                         class="primary-image">
                </div>';
                }

                return '<div class="text-center text-gray-400">
                <i class="fas fa-image fa-lg"></i>
                <span class="block text-xs mt-1">' . __('No image') . '</span>
            </div>';
            })
            ->addColumn('price', function (Product $product) {
                return '<div class="text-right font-semibold">' .
                    number_format($product->price, 2) .
                    ' <span class="text-sm">' . config('app.currency', 'USD') . '</span></div>';
            })
            ->addColumn('created_at', function (Product $product) {
                return '<div class="text-center">
                <div class="font-medium">' . $product->created_at->format('Y-m-d') . '</div>
                <div class="text-xs text-gray-500">' . $product->created_at->format('h:i A') . '</div>
            </div>';
            })
            ->addColumn('updated_at', function (Product $product) {
                return '<div class="text-center">
                <div class="font-medium">' . $product->updated_at->format('Y-m-d') . '</div>
                <div class="text-xs text-gray-500">' . $product->updated_at->format('h:i A') . '</div>
            </div>';
            })
            ->addColumn('actions', function (Product $product) {
                $editUrl = route('admin.dashboard.products.edit', $product);
                $deleteUrl = route('admin.dashboard.products.destroy', $product);
                $imagesUrl = route('admin.dashboard.products.images.index', $product);
                
                $buttons = '<div class="flex items-center justify-center gap-2">';

                // زر معاينة الصور
                $buttons .= '<a href="' . $imagesUrl . '" class="action-btn action-view" title="' . __('View Images') . '"><i class="fas fa-images"></i></a>';


                // زر التعديل
                $buttons .= '<a href="' . $editUrl . '" class="action-btn action-edit" title="' . __('Edit') . '"><i class="fas fa-edit"></i></a>';

                // زر الحذف
                $buttons .= '<button type="button" 
                class="action-btn action-delete" 
                title="' . __('Delete') . '"
                data-url="' . $deleteUrl . '">
                <i class="fas fa-trash"></i>
            </button>';

                $buttons .= '</div>';

                return $buttons;
            })
            ->rawColumns(['primary_image', 'price', 'created_at', 'updated_at', 'actions'])
            ->make(true);
    }

    /**
     * Show product creation form.
     */
    public function create()
    {
        return view('dashboard.products.create');
    }

    /**
     * Store new product.
     *
     * Images are optional and can be added later.
     */
    public function store(StoreProductRequest $request)
    {

        $validated = $request->validated();

        $this->productService->create($validated);

        return redirect()
            ->route('admin.dashboard.products.index')
            ->with('success', __('Product created successfully'));
    }

    /**
     * Show edit form.
     */
    public function edit(Product $product)
    {
        $product->load('images');

        return view(
            'dashboard.products.edit',
            compact('product')
        );
    }

    /**
     * Update product information.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        
        $validated = $request->validated();

        $this->productService->update($product, $validated);

        return redirect()
            ->route('admin.dashboard.products.edit', $product)
            ->with('success', __('Product updated successfully'));
    }

    /**
     * Delete product (soft delete).
     */
    public function destroy(Request $request, Product $product)
    {
        $product->delete();

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('Product deleted successfully')
            ]);
        }

        return redirect()
            ->route('products.index')
            ->with('success', __('Product deleted successfully'));
    }

    /**
     * Upload image to product gallery.
     */
    public function addImage(StoreImageRequest $request, Product $product)
    {
        
        $validated = $request->validated();        
        
        $this->productService->addImage(
            $product,
            $validated,
        );

        return back()->with(
            'success',
            __('Image added successfully')
        );
    }

    /**
     * Set existing image as primary.
     */
    public function setPrimaryImage(ProductImage $image)
    {
        $this->productService->setPrimaryImage($image);

        return back()->with(
            'success',
            __('Primary image updated')
        );
    }

    /**
     * Delete image from gallery.
     */
    public function deleteImage(ProductImage $image)
    {
        $this->productService->deleteImage($image);

        return back()->with(
            'success',
            __('Image deleted successfully')
        );
    }

    /**
     * Display product images gallery.
     */
    public function imagesIndex(Product $product)
    {
        $product->load('images');
        return view('dashboard.products.images', compact('product'));
    }
}
