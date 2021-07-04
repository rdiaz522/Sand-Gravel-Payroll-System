<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashDeductionResource;
use App\Models\CashAdvanceDeduction;
use App\Models\CashDeduction;
use Illuminate\Http\Request;

class CashDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashDeduction = CashDeduction::orderBy('id', 'DESC')->get();
        if($cashDeduction) {
            return CashDeductionResource::collection($cashDeduction);
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
        $this->validate($request , [
            'employee_id' => 'required',
            'cash_deduction' => 'required|numeric',
            'cash_deduction_date' => 'required',
        ]);

        $cashDeduction = new CashDeduction;
        $cashDeduction->employee_id = $request->employee_id;
        $cashDeduction->cash_deduction = $request->cash_deduction;
        $cashDeduction->cash_deduction_date = date('Y-m-d', strtotime($request->cash_deduction_date));
        if($cashDeduction->save()) {
            return new CashDeductionResource($cashDeduction);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cashDeduction = CashDeduction::where('employee_id', $id)->sum('cash_deduction');
        $cashAdvance = CashAdvanceDeduction::where('employee_id', $id)->sum('cash_advance');
        return (float)$cashAdvance - (float)$cashDeduction;
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
        $this->validate($request , [
            'cash_deduction' => 'required|numeric',
            'cash_deduction_date' => 'required',
        ]);

        $cashDeduction = CashDeduction::findOrFail($id);
        $cashDeduction->cash_deduction = $request->cash_deduction;
        $cashDeduction->cash_deduction_date = $request->cash_deduction_date;
        if($cashDeduction->save()) {
            return new CashDeductionResource($cashDeduction);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cashDeduction = CashDeduction::find($id);
        if($cashDeduction->delete()) {
            return new CashDeductionResource($cashDeduction);
        }
    }
}
