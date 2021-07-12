<?php

namespace App\Exports;

use App\Models\Timelogs;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class PayrollLogExport implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';
    
    protected $TIMELOGS = '';
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;


    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    protected $startDate;

    protected $endDate;

    public function __construct($request)
    {
        $this->startDate = date('Y-m-d', strtotime($request->start_date));
        $this->endDate = date('Y-m-d', strtotime($request->end_date));
        $departmentId = getDepartmentNameByName('Processing');
        $this->TIMELOGS = Timelogs::where('department_id', $departmentId)->whereBetween('log_date', [$this->startDate,$this->endDate])->orderBy('id', 'DESC');
    }

    public function map($employeeModel): array
    {   
        $fullName = getEmployeeFullname($employeeModel->employee_id);
        $hourlyRate =  $employeeModel->daily_rate / 8;
        return [
                [
                    $fullName,
                    'Processing',
                    $employeeModel->time_in,
                    $employeeModel->time_out,
                    $employeeModel->total_hours,
                    '₱' . number_format($employeeModel->daily_rate, 2, '.', ''),
                    $hourlyRate,
                    $employeeModel->break_time,
                    '₱' . number_format($employeeModel->total_pay, 2, '.', '')
                ],            
            ];
    }

    public function headings(): array
    {
        $this->DateNow();
        $totalSum = $this->TIMELOGS->sum('total_pay');
        return [
            [
                'PAYDATE DATE: '. $this->DATENOW,
                'DATE:'. $this->startDate . ' - ' . $this->endDate,
                'OVERALL TOTAL PAY - ₱' . $totalSum
            ],
            [
                'EMPLOYEE NAME',
                'POSITION',
                'TIME IN',
                'TIME OUT',
                'TOTAL HOURS',
                'DAILY RATE',
                'HOURLY RATE',
                'BREAK TIME',
                'TOTAL PAY',
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

    public function DateNow()
    {
        $days = [
            0 => 'SUNDAY',
            1 => 'MONDAY',
            2 => 'TUESDAY',
            3 => 'WEDNESDAY',
            4 => 'THURSDAY',
            5 => 'FRIDAY',
            6 => 'SATURDAY',
        ];
        $carbon = Carbon::now('Asia/Manila');
        $this->DATENOW = $carbon->format('Y-m-d');
        $this->DAY = $days[$carbon->dayOfWeek];
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
            'daily_rate',
            'time_in',
            'time_out',
            'break_time',
            'total_hours',
            'total_pay'
        ];
        
        $collections = $this->TIMELOGS->select($selectQuery)->get();
        
        return $collections;
    }
}
