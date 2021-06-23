<?php

namespace App\Http\Controllers;

use App\Http\Resources\EmployeeCollection;
use App\Models\Employees;
use App\Models\EmployeeType;

;
use Illuminate\Http\Request;

class EmployeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employees::orderBy('updated_at', 'DESC')->get();
        return EmployeeCollection::collection($employees);
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
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'employee_type_id' => 'required'
        ]);

        $employees = new Employees;
        $employees->firstname = ucwords($request->firstname);
        $employees->middlename = ucwords($request->middlename);
        $employees->lastname = ucwords($request->lastname);
        $employees->employee_type_id = $request->employee_type_id;
       if($employees->save()) {
            return new EmployeeCollection($employees);
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
        $employees = Employees::findOrFail($id);
        if($employees) {
            return new EmployeeCollection($employees);
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
        //
    
        $this->validate($request , [
            'firstname' => 'required',
            'middlename' => 'required',
            'lastname' => 'required',
            'employee_type_id' => 'required'
        ]);

        $employees = Employees::find($id);
        $employees->firstname = ucwords($request->firstname);
        $employees->middlename = ucwords($request->middlename);
        $employees->lastname = ucwords($request->lastname);
        $employees->employee_type_id = $request->employee_type_id;
       if($employees->save()) {
            return new EmployeeCollection($employees);
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
        //
        $employees = Employees::find($id);
        if($employees->delete()) {
            return new EmployeeCollection($employees);
        }
    }
}
