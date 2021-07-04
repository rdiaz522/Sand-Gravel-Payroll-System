<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DailyPayrollExport;
use App\Exports\DepartmentExpenses;
use App\Exports\DepartmentTotalPay;
use App\Models\Employees;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF as PDF; 
class DailyPayrollExportController extends Controller
{
    public function generateDailyPayroll(Request $request)
    {

        $filename = Carbon::now()->format('Ymdhms').'-Payroll.xlsx';
        $pdfFilename = Carbon::now()->format('Ymdhms').'-PaySlip.pdf';
        Excel::store(new DailyPayrollExport($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = $pdfFilename;
        $reports->start_date = date('Y-m-d', strtotime($request->start_date));
        $reports->end_date = date('Y-m-d', strtotime($request->end_date));
    
        $selectQuery = ['id','firstname','middlename','lastname'];
        $collections = Employees::with(['cashAdvance','cashDeduction', 'timeLogs', 'contributions'])
        ->select($selectQuery)
        ->get();
        $pdf = PDF::loadView('components.pdf', ['collections' => $collections, 'startDate' => date('Y-m-d', strtotime($request->start_date)),'endDate' => date('Y-m-d', strtotime($request->end_date))]);
        $content = $pdf->download()->getOriginalContent();
        Storage::put($pdfFilename,$content) ;

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'WeeklyPayroll are successfully exported.'
            ], 200);
        }
       
    }

    public function generateDepartmentTotalPay(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms').'-TotalPayByDepartment.xlsx';
        Excel::store(new DepartmentTotalPay($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = 'NO PDF';
        $reports->start_date = date('Y-m-d', strtotime($request->start_date));
        $reports->end_date = date('Y-m-d', strtotime($request->end_date));

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Department Pay are successfully exported.'
            ], 200);
        }
    }

    public function generateDepartmentExpenses(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms').'-DepartmentExpenses.xlsx';
        Excel::store(new DepartmentExpenses($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = 'NO PDF';
        $reports->start_date = date('Y-m-d', strtotime($request->start_date));
        $reports->end_date = date('Y-m-d', strtotime($request->end_date));

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Department Expenses are successfully exported.'
            ], 200);
        }
    }
    
}
