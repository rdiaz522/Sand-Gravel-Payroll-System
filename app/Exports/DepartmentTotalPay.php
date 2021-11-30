<?php

namespace App\Exports;

use App\Models\Location;
use App\Models\ExpensesByDepartment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DepartmentTotalPay implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
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

    public function map($departmentModel): array
    {   
        $totalPay = (float)$departmentModel->timeLogs()->whereBetween('log_date', [$this->startDate,$this->endDate])->sum('total_pay');
        $totalExpenses = (float)ExpensesByDepartment::where('department_id', $departmentModel->id)->whereBetween('cash_date',[$this->startDate,$this->endDate])->sum('amount');
        $overAllTotal = $totalPay + $totalExpenses;
        return [
            [
                $departmentModel->name,
                '₱' . number_format($totalPay, 2, '.', ''),
                '₱' . number_format($totalExpenses, 2, '.',''),
                number_format($overAllTotal, 2, '.', '')    
            ]
        ];
    }

    public function headings(): array
    {
        $this->DateNow();

        return [
            [
                'TOTAL PAY BY DEPARTMENT'
            ],
            [
                'GENERATED DATE: '. $this->DATENOW,
                'DATE:'. $this->startDate . ' - ' . $this->endDate
            ],
            [
                'DEPARTMENT',
                'TOTAL PAYMENT',
                'TOTAL EXPENSES',
                'OVERALL TOTAL'
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 35,
            'B' => 35,
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $selectQuery = ['id','name'];
        $collections = Location::with(['timeLogs'])
        ->whereNotIn('name', ['Direct Expense LHB', 'Direct Expenses KBB'])
        ->select($selectQuery)
        ->get();
        return $collections;
    }

    public function DateNow()
    {
        $days = [
            6 => 'SAT',
            0 => 'SUN',
            1 => 'MON',
            2 => 'TUE',
            3 => 'WED',
            4 => 'THU',
            5 => 'FRI',
        ];

        $carbon = Carbon::now('Asia/Manila');
        $this->DATENOW = $carbon->format('Y-m-d');
        $this->DAY = $days[$carbon->dayOfWeek];
    } 
}
