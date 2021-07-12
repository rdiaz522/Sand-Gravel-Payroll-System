<?php

namespace App\Http\Controllers;

use App\Http\Resources\ExpensesByDepartmentResource;
use App\Models\ExpensesByDepartment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExpensesByDepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $expenses = ExpensesByDepartment::orderBy('id','DESC')->get();
        if($expenses) {
            return ExpensesByDepartmentResource::collection($expenses);
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
            'department_id' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric|digits_between:1,6',
            'cash_from' => 'required',
            'cash_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $expenses = new ExpensesByDepartment;
            $expenses->department_id = $request->department_id;
            $expenses->description = $request->description;
            $expenses->amount = $request->amount;
            $expenses->cash_from = $request->cash_from;
            $expenses->cash_date = date('Y-m-d', strtotime($request->cash_date));
            if($expenses->save()) {
                return new ExpensesByDepartmentResource($expenses);
            }
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
        //
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
            'department_id' => 'required',
            'description' => 'required',
            'amount' => 'required|numeric|digits_between:1,6',
            'cash_from' => 'required',
            'cash_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $expenses = ExpensesByDepartment::find($id);
            $expenses->department_id = $request->department_id;
            $expenses->description = $request->description;
            $expenses->amount = $request->amount;
            $expenses->cash_from = $request->cash_from;
            $expenses->cash_date = date('Y-m-d', strtotime($request->cash_date));
            if($expenses->save()) {
                return new ExpensesByDepartmentResource($expenses);
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
        $expenses = ExpensesByDepartment::find($id);
        if($expenses) {
            $expenses->delete();
            return new ExpensesByDepartmentResource($expenses);
        }
    }
}
