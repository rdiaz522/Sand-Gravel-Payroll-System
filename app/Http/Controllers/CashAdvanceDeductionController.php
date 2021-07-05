<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashAdvanceDeductionResource;
use App\Models\CashAdvanceDeduction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CashAdvanceDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashAdvanceDeduction = CashAdvanceDeduction::orderBy('id', 'DESC')->get();
        if($cashAdvanceDeduction) {
            return CashAdvanceDeductionResource::collection($cashAdvanceDeduction);
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
            'cash_advance' => 'required|numeric',
            'description' => 'required',
            'cash_advance_date' => 'required',
        ]);

        $cashAdvanceDeduction = new CashAdvanceDeduction;
        $cashAdvanceDeduction->employee_id = $request->employee_id;
        $cashAdvanceDeduction->cash_advance = $request->cash_advance;
        $cashAdvanceDeduction->description = $request->description;
        $cashAdvanceDeduction->cash_advance_date = date('Y-m-d', strtotime($request->cash_advance_date));
        if($cashAdvanceDeduction->save()) {
            return new CashAdvanceDeductionResource($cashAdvanceDeduction);
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
        $cashAdvanceDeduction = CashAdvanceDeduction::findOrFail($id);
         if($cashAdvanceDeduction) {
             return new CashAdvanceDeductionResource($cashAdvanceDeduction);
         }
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
            'cash_advance' => 'required|numeric',
            'description' => 'required',
            'cash_advance_date' => 'required',
        ]);

        $cashAdvanceDeduction = CashAdvanceDeduction::findOrFail($id);
        $cashAdvanceDeduction->cash_advance = $request->cash_advance;
        $cashAdvanceDeduction->description = $request->description;
        $cashAdvanceDeduction->cash_advance_date = date('Y-m-d', strtotime($request->cash_advance_date));
        if($cashAdvanceDeduction->save()) {
            return new CashAdvanceDeductionResource($cashAdvanceDeduction);
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
        $cashAdvanceDeduction = CashAdvanceDeduction::find($id);
        if($cashAdvanceDeduction->delete()) {
            return new CashAdvanceDeductionResource($cashAdvanceDeduction);
        }
    }
}
