<?php

namespace App\Http\Resources;

use App\Models\Employees;
use Illuminate\Http\Resources\Json\JsonResource;

class CashAdvanceDeductionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employees = Employees::where('id', $this->employee_id)
            ->select(['firstname','middlename','lastname'])
            ->first();
        $fullName = '';
        if($employees instanceof Employees && $employees->exists) {
            $fullName = $employees->lastname . ' ' . $employees->firstname . ', ' . $employees->middlename;
        }
    
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'cash_advance' => $this->cash_advance,
            'description' => $this->description,
            'cash_advance_date' => $this->cash_advance_date,
            'employee_fullname' => $fullName
        ];
    }
}
