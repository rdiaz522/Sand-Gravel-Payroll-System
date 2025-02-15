<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $getDepartmentName = getDepartmentName($this->location_id);
        return [
            'id' => $this->id,
            'name' => $this->name . ' - ' .$getDepartmentName,
            'location_id' => $this->location_id,
        ];
    }
}
