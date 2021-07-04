<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContributionResource;
use App\Models\Contribution;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contributions = Contribution::orderBy('id', 'DESC')->get();
        if($contributions) {
            return ContributionResource::collection($contributions);
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
            'sss' => 'required|numeric',
            'pagibig' => 'required|numeric',
            'philhealth' => 'required|numeric',
            'contribution_date' => 'required',
        ]);

        $contributions = new Contribution;
        $contributions->employee_id = $request->employee_id;
        $contributions->sss = $request->sss;
        $contributions->pagibig = $request->pagibig;
        $contributions->philhealth = $request->philhealth;
        $contributions->contribution_date = date('Y-m-d', strtotime($request->contribution_date));
        if($contributions->save()) {
            return new ContributionResource($contributions);
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
        $fullname = getEmployeeFullname($id);
        $sss = Contribution::where('employee_id',$id)->sum('sss');
        $pagibig = Contribution::where('employee_id',$id)->sum('pagibig');
        $philhealth = Contribution::where('employee_id',$id)->sum('philhealth');
        
        return array('sss' => $sss, 'pagibig' => $pagibig, 'philhealth' => $philhealth, 'fullname' => $fullname);
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
            'sss' => 'required|numeric',
            'pagibig' => 'required|numeric',
            'philhealth' => 'required|numeric',
            'contribution_date' => 'required',
        ]);

        $contributions = Contribution::findOrFail($id);
        $contributions->sss = $request->sss;
        $contributions->pagibig = $request->pagibig;
        $contributions->philhealth = $request->philhealth;
        $contributions->contribution_date = date('Y-m-d', strtotime($request->contribution_date));
        if($contributions->save()) {
            return new ContributionResource($contributions);
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
        $contributions = Contribution::find($id);
        if($contributions->delete()) {
            return new ContributionResource($contributions);
        }
    }
}
