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

class WeeklyPayrollDepartment implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';
    
    protected $EMPLOYEES = '';

    protected $TIMELOGS;
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
        $this->departmentId = $request->department_id;

        $this->TIMELOGS = Timelogs::where('department_id', $this->departmentId)->whereBetween('log_date', [$this->startDate,$this->endDate])->orderBy('id', 'DESC');
        $this->EMPLOYEES = Employees::with(['timeLogs']);
    }

    public function map($employeeModel): array
    {   
        $fullName = $employeeModel->firstname . ' ' . $employeeModel->middlename . '. ' . $employeeModel->lastname;
        $timeLogModel = $timeLogs = $employeeModel->timeLogs()->where('department_id', $this->departmentId)->whereBetween('log_date', [$this->startDate,$this->endDate])->select([
            'daily_rate',
            'log_date',
            'total_pay'
        ]);
        $total = (float)$timeLogModel->sum('total_pay');
        $days = [
            'fullname' => $fullName,
            0 => '₱0',
            1 => '₱0',
            2 => '₱0',
            3 => '₱0',
            4 => '₱0',
            5 => '₱0',
            6 => '₱0',
            'total_pay' => '₱'.$total
        ];
        $timeLogs = $timeLogModel->get();
        if(!empty($timeLogs)) {
            foreach ($timeLogs as $timeLog) {
                $cc = Carbon::parse($timeLog->log_date)->dayOfWeek;
                $days[$cc] = '₱' . $timeLog->daily_rate;
            }
        }

        return [
                $days       
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
                'SUNDAY',
                'MONDAY',
                'TUESDAY',
                'WEDNESDAY',
                'THRUSDAY',
                'FRIDAY',
                'SATURDAY',
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
            'firstname',
            'lastname',
            'middlename',
        ];
        
        $collections = $this->EMPLOYEES->select($selectQuery)->get();

        return $collections;
    }
}
