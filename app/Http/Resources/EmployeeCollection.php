<?php

namespace App\Http\Resources;

use App\Models\EmployeeType;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $employeeType = EmployeeType::findOrFail($this->employee_type_id);
        $employeeTypeName = '';
        if($employeeType instanceof EmployeeType && $employeeType->exists) {
            $employeeTypeName = $employeeType->name;
        }
        $fullname = getEmployeeFullname($this->id);
        return [
            'id' => $this->id,
            'firstname' => $this->firstname,
            'middlename' => $this->middlename,
            'lastname' => $this->lastname,
            'employee_type_id' => $this->employee_type_id,
            'employeeTypeName' => $employeeTypeName,
            'employee_fullname' => $fullname
        ];
    }
}
