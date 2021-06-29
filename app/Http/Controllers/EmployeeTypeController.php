<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeTypeResource;
use App\Models\EmployeeType;
use Illuminate\Http\Request;

class EmployeeTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $employeesType = EmployeeType::orderBy('name', 'ASC')->get();
        return EmployeeTypeResource::collection($employeesType);
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
        //
        $this->validate($request , [
            'name' => 'required|unique:employee_types',
        ]);

        $employeesType = new EmployeeType();
        $employeesType->name = ucwords($request->name);
       if($employeesType->save()) {
            return new EmployeeTypeResource($employeesType);
       }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $employeesType = EmployeeType::findOrFail($id);
         if($employeesType) {
             return new EmployeeTypeResource($employeesType);
         }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function edit(EmployeeType $employeeType)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request , [
            'name' => 'required',
        ]);

        $employeesType = EmployeeType::find($id);
        $employeesType->name = ucwords($request->name);
        if($employeesType->save()) {
            return new EmployeeTypeResource($employeesType);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EmployeeType  $employeeType
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employeesType = EmployeeType::find($id);
        if($employeesType->delete()) {
            return new EmployeeTypeResource($employeesType);
        }
    }
}
