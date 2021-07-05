<?php

namespace App\Exports;

use App\Models\Location;
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

    public function map($employeeModel): array
    {   
        $totalPay = (float)$employeeModel->timeLogs()->whereBetween('log_date', [$this->startDate,$this->endDate])->sum('total_pay');
        return [
            [
                $employeeModel->name,
                '₱' . number_format($totalPay, 2, '.', ''),
            ]
        ];
    }

    public function headings(): array
    {
        $this->DateNow();

        return [
            [
                'GENERATED DATE: '. $this->DATENOW,
                'DATE:'. $this->startDate . ' - ' . $this->endDate
            ],
            [
                'DEPARTMENT',
                'TOTAL PAYMENT'
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
