<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportResource extends JsonResource
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
            'report_type' => $this->report_type,
            'report_excel' => $this->report_excel,
            'report_pdf' => $this->report_pdf,
            'start_date' =>  $this->start_date,
            'end_date' =>  $this->end_date,
        ];
    }
}
