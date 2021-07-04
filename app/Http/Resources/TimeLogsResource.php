<?php

namespace App\Http\Resources;

use App\Models\BreakHour;
use App\Models\Employees;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Http\Resources\Json\JsonResource;

class TimeLogsResource extends JsonResource
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

        $departmentName = '';
        $departments = Location::findOrFail($this->department_id);
        if($departments instanceof Location && $departments->exists) {
            $departmentName = $departments->name;
        }
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'department_id' => $this->department_id,
            'daily_rate' => $this->daily_rate,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'break_time' => $this->break_time,
            'total_hours' => $this->total_hours,
            'log_date' => $this->log_date,
            'employee_fullname' => $fullName,
            'departmentName' => $departmentName,
        ];
    }
}
