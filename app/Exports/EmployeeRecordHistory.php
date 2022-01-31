<?php

namespace App\Exports;

use App\Models\Employees;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Timelogs;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class EmployeeRecordHistory implements FromCollection, Responsable, WithColumnWidths, WithHeadings, WithMapping
{
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;

    protected $TIMELOGS;

    protected $startDate;

    protected $endDate;

    use Exportable;

    public function __construct($request)
    {
        $this->startDate = date('Y-m-d', strtotime($request->start_date));
        $this->endDate = date('Y-m-d', strtotime($request->end_date));
        $timelogQuery = Timelogs::whereBetween('log_date', [$this->startDate,$this->endDate]);
        if(!empty($request->employee_id) && $request->employee_id !== '') {
            $this->TIMELOGS = $timelogQuery->where('employee_id', $request->employee_id);
        }else {
            $this->TIMELOGS = $timelogQuery;
        }
    }

    public function map($timeLogsModel): array
    {
        $fullName = getEmployeeFullname($timeLogsModel->employee_id);
        $department = getDepartmentById($timeLogsModel->department_id);
        $location = getLocationNameById($timeLogsModel->location_id);
        $timeIn  = $timeLogsModel->time_in . ' - ' . date('m-d-Y', strtotime($timeLogsModel->log_date));
        $timeOut = $timeLogsModel->time_out . ' - ' . date('m-d-Y', strtotime($timeLogsModel->log_date2));
        $totalPay = $timeLogsModel->total_pay;
        return [
           [
            $fullName,
            $department,
            $location,
            $timeIn,
            $timeOut,
            $timeLogsModel->break_time,
            $timeLogsModel->total_hours,
            number_format($totalPay, 2)
           ]
        ];
    }

    public function headings(): array
    {
        return [
            [
                'FROM: ' . date('m-d-Y', strtotime($this->startDate)),
                'TO: ' . date('m-d-Y', strtotime($this->endDate)),
            ],
            [
                'EMPLOYEE NAME',
                'DEPARTMENT',
                'LOCATION',
                'TIME IN',
                'TIME OUT',
                'BREAK TIME',
                'TOTAL HOURS',
                'TOTAL PAY'
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35,
            'C' => 35,
            'D' => 35,
            'E' => 35,
            'F' => 35,
            'G' => 35,
            'H' => 35,
            'I' => 35
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $selectQuery = [
            'id',
            'employee_id',
            'department_id',
            'location_id',
            'time_in',
            'log_date',
            'time_out',
            'log_date2',
            'total_hours',
            'break_time',
            'total_pay',
        ];

        $collections = $this->TIMELOGS->select($selectQuery)
            ->orderBy('log_date', 'DESC')->get();

        return $collections;
    }
}
