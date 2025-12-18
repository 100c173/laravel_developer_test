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
        // Assuming the Product model has relationships:
        // - 'user' (belongsTo)
        // - 'images' (hasMany)
        return [
            'id' => $this->id,
            'title' => [
                'en' => $this->title_en,
                'ar' => $this->title_ar,
            ],
            'description' => [
                'en' => $this->description_en,
                'ar' => $this->description_ar,
            ],
            'slug' => $this->slug,
            'price' => number_format($this->price, 2), // Format price for display
            //'created_by' => new UserResource($this->whenLoaded('user')), // Assuming UserResource exists
            'images' => ProductImageResource::collection($this->whenLoaded('images')), // Assuming ProductImageResource exists
            'primary_image_url' => $this->images->where('is_primary', true)->first()->image_path ?? null, // Quick access to primary image
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
