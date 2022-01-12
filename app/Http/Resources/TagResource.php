<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TagResource extends JsonResource
{
    public function toArray($request)
    {
        $return = parent::toArray($request);
        $return['type'] = 'tag';

        return $return;
    }
}
