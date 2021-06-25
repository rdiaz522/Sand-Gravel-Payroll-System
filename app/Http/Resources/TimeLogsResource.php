<?php

namespace App\Http\Resources;

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
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'position_id' => $this->position_id,
            'daily_rate' => $this->daily_rate,
            'time_in' => $this->time_in,
            'time_out' => $this->time_out,
            'break_time' => $this->break_time,
            'total_hours' => $this->total_hours,
            'log_date' => $this->log_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
