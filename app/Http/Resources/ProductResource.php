<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,
            'cover_image' => $this->cover_image,
            'category' => $this->category,
            'images' => $this->images,
            'installment_plans' => $this->installmentPlans,
            // Variants المتاحة فقط
            'available_variants' => $this->when(isset($this->available_variants), $this->available_variants),
            // Attributes منظمة حسب النوع
            'grouped_attributes' => $this->when(isset($this->grouped_attributes), $this->grouped_attributes),
        ];

    }
}
