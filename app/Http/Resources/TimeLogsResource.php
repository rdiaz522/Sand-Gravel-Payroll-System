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
        $totalHours =  $this->total_hours;
        $timeIn =  $this->time_in;
        $timeOut = $this->time_out;
        $departmentName = '';
        $departments = Location::findOrFail($this->department_id);
        if($departments instanceof Location && $departments->exists) {
            $departmentName = $departments->name;
        }
        
        if(strtolower($departmentName) !== 'processing') {
            $totalHours = 'Daily';
        }

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'department_id' => $this->department_id,
            'daily_rate' => (float)$this->daily_rate,
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'break_time' => $this->break_time,
            'total_hours' => $totalHours,
            'log_date' => $this->log_date,
            'log_date2' => $this->log_date2,
            'employee_fullname' => $fullName,
            'departmentName' => $departmentName,
        ];
    }
}
