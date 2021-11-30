<?php

namespace App\Http\Resources;

use App\Models\Location;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesByDepartmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $getDepartName = Location::where('id',$this->department_id)->first();
        $name = '';
        if($getDepartName instanceof Location && $getDepartName->exists) {
            $name = $getDepartName->name;
        }
        return [
            'id' => $this->id,
            'department_id' => $this->department_id,
            'description' => $this->description,
            'amount' => (float)$this->amount,
            'cash_from' => $this->cash_from,
            'cash_date' => $this->cash_date,
            'departmentName' => $name
        ];
    }
}
