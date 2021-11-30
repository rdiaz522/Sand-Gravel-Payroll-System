<?php

namespace App\Http\Resources;

use App\Models\Employees;
use Illuminate\Http\Resources\Json\JsonResource;

class CashDeductionResource extends JsonResource
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
            'cash_deduction' => (float)$this->cash_deduction,
            'new_cash_advance_balance' => (float)$this->new_cash_advance_balance,
            'cash_deduction_date' => $this->cash_deduction_date,
            'cash_advance_id' => $this->cash_advance_id,
            'employee_fullname' => $fullName,
            'description' => getCashAdvanceById($this->cash_advance_id),
            'cash_amount' => getCashAdvanceAmountById($this->cash_advance_id)
        ];
    }
}
