<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class DailyPayrollExport implements FromCollection, Responsable, WithHeadings, WithColumnWidths, WithMapping
{
    use Exportable;

    protected $DATENOW = '';

    protected $DAY = '';
    /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'dailypayroll.xlsx';

    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;


    private $headers = [
        'Content-Type' => 'text/csv',
    ];

    /**
    * @var Invoice $invoice
    */
    public function map($employee): array
    {   
        $fullName = $employee->firstname . ' ' . $employee->middlename . ' ' . $employee->lastname;
        $hourlyRate = (float)$employee->daily_rate / 8;
        $totalPay = (float)$hourlyRate * (float)$employee->total_hours;
        return [
            [
                $fullName,
                $employee->name,
                $employee->time_in,
                $employee->time_out,
                $employee->total_hours,
                $employee->daily_rate,
                $hourlyRate,
                $employee->break_time,
                $totalPay
            ]
        ];
    }

    public function headings(): array
    {
        $this->DateNow();

        return [
            [
                'DATE: '. $this->DATENOW,
                'DAY: '. $this->DAY
            ],
            [
                'NAMES',
                'POSITION',
                'TIME IN',
                'TIME OUT',
                'TOTAL HOURS',
                'DAILY RATE',
                'HOURLY RATE',
                'BREAK TIME',
                'TOTAL PAY'
            ]
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,            
            'F' => 20,
            'G' => 20,
            'H' => 20,
            'I' => 20,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $selectQuery = [
            'E.id',
            'E.firstname',
            'E.middlename',
            'E.lastname',
            'L.daily_rate',
            'L.time_in',
            'L.time_out',
            'L.break_time',
            'L.total_hours',
            'L.log_date',
            'P.name'
        ];
       $collections = DB::table('employees AS E')
       ->leftjoin('employee_timelogs as L','E.id','=','L.employee_id')
       ->leftjoin('positions as P', 'L.position_id','=','P.id')
       ->select($selectQuery)->get();

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

    public function failed(Throwable $exception): void
    {
        // handle failed export
    }
}
