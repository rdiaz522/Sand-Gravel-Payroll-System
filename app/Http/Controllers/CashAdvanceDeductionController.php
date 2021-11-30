<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashAdvanceDeductionResource;
use App\Models\CashAdvanceDeduction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CashAdvanceDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cashAdvanceDeduction = CashAdvanceDeduction::orderBy('updated_at', 'desc')->get();
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
        $validator = Validator::make($request->toArray(), [
            'employee_id' => 'required',
            'cash_advance' => 'required|numeric',
            'cash_advance_desc_id' => 'required',
            'cash_advance_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $isExistCashAdvance = $this->checkCashAdvance($request->cash_advance_desc_id, $request->employee_id);
            if(!empty($isExistCashAdvance)) {
                $cashAdvance = DB::table('cash_advance_deductions')
                ->where('employee_id', $request->employee_id)
                ->where('cash_advance_description', $request->cash_advance_desc_id);
                $cashAmount = $cashAdvance->pluck('cash_advance')->first();
                $newCashAmount = $cashAmount + $request->cash_advance;
                $cashAdvance->update([
                    'cash_advance' => $newCashAmount,
                    'cash_advance_date' => date('Y-m-d', strtotime($request->cash_advance_date))
                ]);
            } else {
                $cashAdvanceDeduction = new CashAdvanceDeduction;
                $cashAdvanceDeduction->employee_id = $request->employee_id;
                $cashAdvanceDeduction->cash_advance = (float)$request->cash_advance;
                $cashAdvanceDeduction->cash_advance_description = $request->cash_advance_desc_id;
                $cashAdvanceDeduction->cash_advance_date = date('Y-m-d', strtotime($request->cash_advance_date));
                if($cashAdvanceDeduction->save()) {
                    return new CashAdvanceDeductionResource($cashAdvanceDeduction);
                }
            }
        }
    }

    protected function checkCashAdvance($id, $employeeId)
    {
        $cashAdvance = CashAdvanceDeduction::where('employee_id', $employeeId)
        ->where('cash_advance_description', $id)
        ->select('id')
        ->first();
        if($cashAdvance instanceof CashAdvanceDeduction && $cashAdvance->exists) {
            return $cashAdvance->id;
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
        $cashAdvanceDeduction = CashAdvanceDeduction::where('employee_id', $id)->get();
         if($cashAdvanceDeduction) {
             return CashAdvanceDeductionResource::collection($cashAdvanceDeduction);
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
        $validator = Validator::make($request->toArray(), [
            'cash_advance' => 'required|numeric',
            'cash_advance_desc_id' => 'required',
            'cash_advance_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $cashAdvanceDeduction = CashAdvanceDeduction::findOrFail($id);
            $cashAdvanceDeduction->cash_advance = (float)$request->cash_advance;
            $cashAdvanceDeduction->cash_advance_description = $request->cash_advance_desc_id;
            $cashAdvanceDeduction->cash_advance_date = date('Y-m-d', strtotime($request->cash_advance_date));
            if($cashAdvanceDeduction->save()) {
                return new CashAdvanceDeductionResource($cashAdvanceDeduction);
            }
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
