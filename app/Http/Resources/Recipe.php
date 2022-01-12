<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Recipe extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'tags' =>  $this->tags,
            'serves' => $this->serves,
            'time' => $this->time,
            'description' => $this->description,
            'ingredients' => $this->ingredients,
            'keyEquipment' => $this->keyEquipment,
            'headNote' => $this->headNote,
            'instructions' => $this->instructions,
            'imageUrl' => $this->imageUrl,
            'publish_date' => $this->publish_date,
            'is_published' => $this->is_published,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
