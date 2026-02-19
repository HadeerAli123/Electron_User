<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRepositoryInterface;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
class ProductController extends Controller
{
    protected $productRepository;
    protected $categoryRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->productRepository = $productRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all products with filters
     */
    public function index(Request $request)
    {
       
    }

    /**
     * Get product details
     */
    
    public function show($id)
    {
        $productData = $this->productRepository->getProductVariantsGrouped($id);

    $productResource = new ProductResource((object)[
        'id' => $productData['product']->id,
        'name' => $productData['product']->name,
        'description' => $productData['product']->description,
        'price' => $productData['product']->price,
        'stock' => $productData['product']->stock,
        'cover_image' => $productData['product']->cover_image,
        'category' => $productData['product']->category,
        'images' => $productData['product']->images,
        'installmentPlans' => $productData['product']->installmentPlans,
        'available_variants' => $productData['variants'],
        'grouped_attributes' => $productData['grouped_attributes'],
    ]);

    return response()->json([
        'success' => true,
        'data' => $productResource,
    ]);
}


    /**
     * Get products by category
     */
    public function getByCategory($categoryId)
    {
        try {
            // Verify category exists
            $this->categoryRepository->find($categoryId);
            
            $products = $this->productRepository->getByCategory($categoryId);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        try {
            $searchTerm = $request->input('q');

            if (empty($searchTerm)) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى إدخال كلمة البحث',
                ], 422);
            }

            $products = $this->productRepository->search($searchTerm);

            return response()->json([
                'success' => true,
                'data' => $products,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Get latest products
     */
   

    /**
     * Check product availability
     */
    // public function checkAvailability($id, Request $request)
    // {
    //     try {
    //         $quantity = $request->input('quantity', 1);
    //         $product = $this->productRepository->findOrFail($id);
    //         $available = $product->hasStock($quantity);

    //         return response()->json([
    //             'success' => true,
    //             'available' => $available,
    //         ]);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => $e->getMessage(),
    //         ], 400);
    //     }
    // }
}
