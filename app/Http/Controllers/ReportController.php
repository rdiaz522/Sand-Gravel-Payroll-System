<?php

namespace App\Http\Controllers;

use App\Http\Resources\ReportResource;
use App\Models\Employees;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reports = Report::orderBy('id', 'DESC')->get();
        if($reports) {
            return ReportResource::collection($reports);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {   
        $report = Report::findOrFail($id);
        if($report) {
            $path = storage_path('app/public/'. $report->report_excel);
            return response()->download($path);
        }

        return null;
    }
    
    /**
     * 
     */
    public function generatePaySlip($id)
    {;
        $report = Report::findOrFail($id);
        if($report->report_pdf !== 'NO PDF') {
            $path = storage_path('app/public/'. $report->report_pdf);
            return response()->download($path);
        }

        return 0;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reports = Report::find($id);
        if ($reports->delete()) {
            Storage::disk('public')->delete($reports->report_excel);
            Storage::disk('public')->delete($reports->report_pdf);
            return new ReportResource($reports);
        }
    }
}
