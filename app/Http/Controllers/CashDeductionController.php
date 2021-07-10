<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashDeductionResource;
use App\Models\CashAdvanceDeduction;
use App\Models\CashDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            'employee_cash_advance_id' => 'required'
        ]);

        $cashDeduction = new CashDeduction;
        $cashDeduction->employee_id = $request->employee_id;
        $cashDeduction->cash_advance_id = $request->employee_cash_advance_id;
        $cashDeduction->cash_deduction = $request->cash_deduction;
        $cashDeduction->new_cash_advance_balance = $request->new_cash_advance_balance;
        $cashDeduction->cash_deduction_date = date('Y-m-d', strtotime($request->cash_deduction_date));
        if($cashDeduction->save()) {
            $deduction = $this->checkCashAdvance($request->employee_cash_advance_id, $request->employee_id);
            if($deduction) {
                return new CashDeductionResource($cashDeduction);
            }
        }
    }

    protected function checkCashAdvance($id, $employeeId)
    {
        $cashDeduction = CashDeduction::where('employee_id', $employeeId)
        ->where('cash_advance_id', $id)
        ->select(['cash_advance_id','new_cash_advance_balance'])
        ->orderBy('id','desc')
        ->first();
        if($cashDeduction instanceof CashDeduction && $cashDeduction->exists) {
            $newCashAmount = $cashDeduction->new_cash_advance_balance;
            $cashAdvance = DB::table('cash_advance_deductions')
            ->where('employee_id', $employeeId)
            ->where('id', $cashDeduction->cash_advance_id)
            ->update([
                'cash_advance' => (float)$newCashAmount
            ]);
            
            if($cashAdvance) {
                return true;
            }
        }

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $cashAdvance = CashAdvanceDeduction::where('id', $id)->sum('cash_advance');

        return (float)$cashAdvance;
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
