<?php

namespace App\Repositories;

use App\Models\Category;
use App\Interfaces\CategoryRepositoryInterface;
use Illuminate\Support\Facades\Storage;
class CategoeryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

///////////store 
   public function store(array $data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
        
        $path = $data['image']->store('categories', 'public');
        
        $data['image'] = $path;
    }

        return Category::create($data);
    }


////////////update
public function update($id, array $data, $imageFile = null)
    {
        $category = $this->find($id);

        if ($imageFile) {
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }

            $imagePath = $imageFile->store('categories', 'public');
            $data['image'] = $imagePath;
        }

        $category->update($data);
        return $category;
    }


/////////////getParentCategories
///// بترجع الاقسام الرئيسية مع  الي تحتها بس مش كل الشجرة 
public function getParentCategories($perPage = 10)
{
    return $this->model
                ->whereNull('parent_id')
                ->with('children') // eager load children
                ->paginate($perPage);
}

/////////////getCategoryWithChildren 
///////بديها الاي دي بتاع الكاتيجوري وترجع المستوي الي تحتها مش كل الشجرة 
public function getCategoryWithChildren(int $categoryId, $perPage = 10)
{
    $category = $this->model->findOrFail($categoryId);

    return $category->children()->paginate($perPage); // pagination على الأطفال فقط
}


    ////////////getChildCategories 
 public function getChildCategories(int $parentId)
    {
        return $this->model->where('parent_id', $parentId)->get();
    }
    
    ////////////getCategoryWithProducts
    public function getCategoryWithProducts(int $categoryId)
    {
        return $this->model->with('products.images')->findOrFail($categoryId);
    }
    
    ////////////getCategoryTree
   public function getCategoryTree()
{
    return $this->model->whereNull('parent_id')->with('children.children')->get();
}

}