<?php

namespace App\Exports;

use App\Http\Resources\TimeLogsResource;
use App\Models\Contribution;
use App\Models\Employees;
use App\Models\Location;
use App\Models\Timelogs;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DailyPayrollExport implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping, WithStyles
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';

    protected $TOTAL = 0;

    protected $DATA =[];
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
        $selectQuery = ['id','firstname','middlename','lastname'];
        $this->DATA = Employees::with(['cashAdvance','cashDeduction', 'timeLogs', 'contributions'])
        ->select($selectQuery)
        ->get();
    }

    public function styles(Worksheet $sheet)
    {
        $count = count($this->DATA) + 2;
        // Add cell with SUM formula to last row
        $sheet->setCellValue("C1", '="OVERALL TOTAL PAY :₱"&SUM(J3:J' . $count . ')');
    }

    public function map($employeeModel): array
    {   
            $fullname = $employeeModel->lastname . ' ' . $employeeModel->firstname . ' ' . $employeeModel->middlename . '.';
            $departmentAssigned = [];
            $total_each_pay = [];
            $SSS = 0;
            $pagibig = 0;
            $philhealth = 0;
            $departments = Location::all();
            $cashDeductions = collect($employeeModel->cashDeduction()->whereBetween('cash_deduction_date', [$this->startDate,$this->endDate])->get());
            $overAllTotalCashDedcution = $cashDeductions->sum('cash_deduction');
            $cashAdvancesCollection = $employeeModel->cashAdvance()->get();
            $cashAdvanceAmount = [];
            $totalCashDeductions = [];

            $timelogs = collect($employeeModel->timeLogs()->whereBetween('log_date', [$this->startDate,$this->endDate])->get());
            if($cashAdvancesCollection instanceof Collection && !$cashAdvancesCollection->isEmpty()) {
                foreach ($cashAdvancesCollection as $item) {
                    $deduction = $cashDeductions->where('cash_advance_id', $item->id)->sum('cash_deduction');
                    $getCashAdvanceName = getCashAdvanceDescription($item->cash_advance_description);
                    $amount = (float)$item->cash_advance;
                    $totalCashDeductions[] = $getCashAdvanceName . ' - ₱' . number_format($deduction, 2, '.', '');
                    $cashAdvanceAmount[] =  $getCashAdvanceName . ' - ₱' . number_format($amount, 2, '.', '');
                }
            }

            if($departments instanceof Collection && !$departments->isEmpty()) {
                foreach($departments as $department) {
                    $departmentAssigned[] = strtoupper($department->name);
                    $amount = (float)$timelogs->where('department_id',$department->id)->sum('total_pay');
                    $total_each_pay[] ='₱' . number_format($amount, 2, '.', '');
                }
            }

            $netPay = (float)$timelogs->sum('total_pay');
            $contributions = $employeeModel->contributions()
            ->whereBetween('contribution_date', [$this->startDate,$this->endDate])
            ->latest()->first();

            if($contributions instanceof Contribution && $contributions->exists) {
                $SSS = (float)$contributions->sss;
                $pagibig = (float)$contributions->pagibig;
                $philhealth = (float)$contributions->philhealth;
            }
            $totalContribution = (float)$SSS + (float)$pagibig + (float)$philhealth;
            $gross = (float)$netPay - (float)$overAllTotalCashDedcution - (float)$totalContribution;
        return [
            [
                $fullname,
                '(' .implode(" | ", $departmentAssigned) . ')',
                '(' .implode(" | ", $total_each_pay) . ')',
                '₱' . number_format($netPay, 2, '.', ''),
                '(' .implode(" | ", $totalCashDeductions) . ')',
                '(' .implode(" | ", $cashAdvanceAmount) . ')',
                '₱' . number_format($SSS, 2, '.', ''),
                '₱' . number_format($pagibig, 2, '.', ''),
                '₱' . number_format($philhealth, 2, '.', ''),
                number_format($gross, 2, '.', ''),
            ],
        ];
    }

    public function headings(): array
    {
        $this->DateNow();
        return [
            [
                'PAYDATE DATE: '. $this->DATENOW,
                'DATE:'. $this->startDate . ' - ' . $this->endDate,
            ],
            [
                'FULLNAME',
                'DEPARTMENT',
                'TOTAL PAY OF EACH DEPARTMENT',
                'GROSS',
                'TOTAL CASH DEDUCTION',
                'TOTAL CASH ADVANCE BALANCE',
                'SSS',
                'PAGIBIG',
                'PHILHEALTH',
                'NET.PAY',
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
        return $this->DATA;
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
