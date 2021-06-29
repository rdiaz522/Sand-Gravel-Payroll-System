<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DailyPayrollExport;
use Maatwebsite\Excel\Facades\Excel;

class DailyPayrollExportController extends Controller
{
    public function generateDailyPayroll()
    {
       return new DailyPayrollExport;
    }
}
