<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\DailyPayrollExport;
use App\Exports\DepartmentExpenses;
use App\Exports\PayrollLogExport;
use App\Exports\DepartmentTotalPay;
use App\Exports\EmployeeRecordHistory;
use App\Exports\WeeklyPayrollDepartment;
use App\Exports\EmployeeeRecordHistory;
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
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date = dateFormat($request->end_date);

        $selectQuery = ['id','firstname','middlename','lastname'];
        $collections = Employees::with(['cashAdvance','cashDeduction', 'timeLogs', 'contributions'])
        ->orderBy('lastname', 'ASC')
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
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date =  dateFormat($request->end_date);

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
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date = dateFormat($request->end_date);

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Department Expenses are successfully exported.'
            ], 200);
        }
    }

    public function generateProcessingLogBook(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms').'-DailyProcessingLogBook.xlsx';
        Excel::store(new PayrollLogExport($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = 'NO PDF';
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date = dateFormat($request->start_date);

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Daily Processing are successfully exported.'
            ], 200);
        }
    }

    public function generateWeeklyPayrollDepartment(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms').'-WeeklyPayrollByDepartment.xlsx';
        Excel::store(new WeeklyPayrollDepartment($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = 'NO PDF';
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date = dateFormat($request->end_date);

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Weekly Payroll are successfully exported.'
            ], 200);
        }
    }

    public function generateEmployeeRecordHistory(Request $request)
    {
        $filename = Carbon::now()->format('Ymdhms').'-EmployeeRecordHistory.xlsx';
        Excel::store(new EmployeeRecordHistory($request), $filename);
        $fullPath = Storage::disk('public')->path($filename);
        $reports = new Report;
        $reports->report_type = $request->report_type;
        $reports->report_excel = $filename;
        $reports->report_pdf = 'NO PDF';
        $reports->start_date = dateFormat($request->start_date);
        $reports->end_date = dateFormat($request->end_date);

        if($reports->save()) {
            return response()->json([
                'data' => $fullPath,
                'message' => 'Employee Record History are successfully exported.'
            ], 200);
        }
    }
}
