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
   public function store(array $data, $imageFile = null)
    {
        if ($imageFile) {
            $imgPath = $imageFile->store('categories', 'public');
            $data['image'] = $imgPath;
        }

        return Category::create($data);
    }


////////////update
public function update($id, array $data, $imageFile = null)
    {
        $category = $this->find($id);

        // Handle new image upload
        if ($imageFile) {
            // Delete old image if exists
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

public function getParentCategories()
{
    return $this->model->whereNull('parent_id')->with('children')->get();
}

/////////////getCategoryWithChildren 
 public function getCategoryWithChildren(int $categoryId)
    {
        return $this->model->with('children')->findOrFail($categoryId);
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