<?php

namespace App\Exports;

use App\Models\ExpensesByDepartment;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Location;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class DepartmentExpenses implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';

    protected $DEPRTMENTCOLLECTION;
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;


    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    protected $startDate;

    protected $endDate;

    protected $departmentId;

    protected $departmentName;

    protected $totalAmount;

     public function __construct($request)
    {
        $this->startDate = date('Y-m-d', strtotime($request->start_date));
        $this->endDate = date('Y-m-d', strtotime($request->end_date));
        $this->departmentId = $request->department_id;
        $this->departmentName = getDepartmentName($request->department_id);
        $this->DEPRTMENTCOLLECTION = ExpensesByDepartment::where('department_id', $this->departmentId)->whereBetween('cash_date',[$this->startDate,$this->endDate]);
    }

    public function map($employeeModel): array
    {   
        return [
            [
                $employeeModel->cash_date,
                $employeeModel->description,
                '₱' . number_format($employeeModel->amount, 2, '.', ''),
                $employeeModel->cash_from
            ]
        ];
    }

    public function headings(): array
    {
        $this->DateNow();

        return [
            [
                'GENERATED DATE: '. $this->DATENOW,
                'DATE: '. $this->startDate . ' - ' . $this->endDate,
                'DEPARTMENT NAME: ' . strtoupper($this->departmentName),
                'TOTAL: ₱' . number_format($this->DEPRTMENTCOLLECTION->sum('amount'), 2, '.', '')
            ],
            [
                'DATE',
                'DESCRIPTION',
                'AMOUNT',
                'CASH FROM'
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
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {   
        $selectQuery = ['id','department_id','description','amount','cash_from','cash_date'];
        $collections = $this->DEPRTMENTCOLLECTION->select($selectQuery)->get();
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
