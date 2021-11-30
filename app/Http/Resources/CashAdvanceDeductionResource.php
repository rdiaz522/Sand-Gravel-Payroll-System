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
        $description = getCashAdvanceDescription($this->cash_advance_description);
        return [
            'id' => $this->id,
            'name' => $description,
            'employee_id' => $this->employee_id,
            'cash_advance' => (float)$this->cash_advance,
            'cash_advance_description' => $this->cash_advance_description,
            'cash_advance_date' => $this->cash_advance_date,
            'employee_fullname' => $fullName,
            'description' => $description
        ];
    }
}
