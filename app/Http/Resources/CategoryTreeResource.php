<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryTreeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
  return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'parent_id' => $this->parent_id,
            
            // الأطفال (recursive)
            'children' => CategoryTreeResource::collection($this->whenLoaded('children')),
            
            // عدد الأطفال
            'children_count' => $this->when(
                $this->relationLoaded('children'),
                fn() => $this->children->count()
            ),
        ];    }
}
