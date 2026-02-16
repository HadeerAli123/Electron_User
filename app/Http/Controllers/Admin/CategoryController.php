<?php

namespace App\Http\Controllers\Admin;
use App\Http\Requests\StoreCategoryRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCategoryRequest;
use App\Repositories\CategoeryRepository;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CategoryTreeResource;
class CategoryController extends Controller
{
    // ========================================
    // Public Methods
    // ========================================
    private $categoryRepo;

public function __construct( CategoeryRepository $categoryRepo)
{
    $this->categoryRepo = $categoryRepo;
}
 
    public function index( )
    {
        try {
            $categories =$this->categoryRepo->all();

            return response()->json([
                'success' => true,
                'data' => CategoryResource::collection($categories),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getTree()
    {
        try {
            $tree = $this->categoryRepo->getCategoryTree();

            return response()->json([
                'success' => true,
                'data' => CategoryTreeResource::collection($tree),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getParents()
    {
        try {
            $parents = $this->categoryRepo->getParentCategories();

            return response()->json([
                'success' => true,
                'data' => CategoryTreeResource::collection($parents),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $category = $this->categoryRepo->find($id);

            if (!$category) {
                return response()->json([
                    'success' => false,
                    'message' => 'الفئة غير موجودة',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => new CategoryResource($category),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    public function getWithChildren($id)
    {
        try {
            $category = $this->categoryRepo->getCategoryWithChildren($id);

            return response()->json([
                'success' => true,
                'data' => new CategoryTreeResource($category),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة',
            ], 404);
        }
    }

    public function getWithProducts($id)
    {
        try {
            $category = $this->categoryRepo->getCategoryWithProducts($id);

            return response()->json([
                'success' => true,
                'data' => $category,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'الفئة غير موجودة',
            ], 404);
        }
    }

    public function getChildren($parentId )
{
    try {

        $children = $this->categoryRepo->getChildCategories($parentId);

        return response()->json([
            'success' => true,
                'data' => CategoryResource::collection($children),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
}

  

   public function store(StoreCategoryRequest $request)
{
    try {

        $category = $this->categoryRepo->store(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم إنشاء الفئة بنجاح',
             'data' => new CategoryResource($category),
        ], 201);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
}
  
public function update(UpdateCategoryRequest $request, $id)
{
    try {

        $category = $this->categoryRepo->update(
            $id,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الفئة بنجاح',
                'data' => new CategoryResource($category),
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 400);
    }
}



}