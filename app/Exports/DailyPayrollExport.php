<?php

namespace App\Exports;

use App\Models\Contribution;
use App\Models\Employees;
use App\Models\Location;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DailyPayrollExport implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';

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
    }

    public function map($employeeModel): array
    {   
            $fullname = $employeeModel->lastname . ' ' . $employeeModel->firstname . ' ' . $employeeModel->middlename;
            $departmentAssigned = [];
            $total_each_pay = [];
            $SSS = 0;
            $pagibig = 0;
            $philhealth = 0;
            $departments = Location::all();
            $totalCashAdvances =  (float)$employeeModel->cashAdvance()->sum('cash_advance');;

            $totalCashDeductions = (float)$employeeModel->cashDeduction()
            ->whereBetween('cash_deduction_date', [$this->startDate,$this->endDate])
            ->sum('cash_deduction');

            $overTotalCashAdvance = (float)$totalCashAdvances - (float)$totalCashDeductions;
            if($departments instanceof Collection && !$departments->isEmpty()) {
                foreach($departments as $department) {
                    $departmentAssigned[] = $department->name;
                    $total_each_pay[] =  (float)$employeeModel->timeLogs()
                    ->where('department_id',$department->id)->whereBetween('log_date', [$this->startDate,$this->endDate])
                    ->sum('total_pay');
                }
            }
            $netPay = $employeeModel->timeLogs()->whereBetween('log_date', [$this->startDate,$this->endDate])->sum('total_pay');
            $contributions = $employeeModel->contributions()
            ->whereBetween('contribution_date', [$this->startDate,$this->endDate])
            ->latest()->first();

            if($contributions instanceof Contribution && $contributions->exists) {
                $SSS = (float)$contributions->sss;
                $pagibig = (float)$contributions->pagibig;
                $philhealth = (float)$contributions->philhealth;
            }
            $totalContribution = (float)$SSS + (float)$pagibig + (float)$philhealth;
            $gross = (float)$netPay - (float)$totalCashDeductions - (float)$totalContribution;
        
        return [
            [
                $fullname,
                '(' .implode(", ", $departmentAssigned) . ')',
                '₱' . '(' .implode(", ", $total_each_pay) . ')',
                '₱' . $netPay,
                '₱' . $totalCashAdvances,
                '₱' . $totalCashDeductions,
                '₱' . $overTotalCashAdvance,
                '₱' . $SSS,
                '₱' . $pagibig,
                '₱' . $philhealth,
                '₱' . $gross
            ]
        ];
    }

    public function headings(): array
    {
        $this->DateNow();

        return [
            [
                'PAYDATE DATE: '. $this->DATENOW,
                'DATE:'. $this->startDate . ' - ' . $this->endDate
            ],
            [
                'FULLNAME',
                'DEPARTMENT',
                'TOTAL PAY OF EACH DEPARTMENT',
                'NET.PAY',
                'TOTAL CASH ADVANCE',
                'CASH DEDUCTION',
                'CASH ADVANCE BALANCE',
                'SSS',
                'PAGIBIG',
                'PHILHEALTH',
                'GROSS'
            ]
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
            'I' => 35,
            'J' => 35
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {  
        $selectQuery = ['id','firstname','middlename','lastname'];
        $collections = Employees::with(['cashAdvance','cashDeduction', 'timeLogs', 'contributions'])
        ->select($selectQuery)
        ->get();

        return $collections;
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
}
