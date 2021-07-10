<?php

namespace App\Http\Controllers;

use App\Http\Resources\CashAdvanceResource;
use App\Models\CashAdvanceDescription;
use Illuminate\Http\Request;

class CashAdvanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $cashAdvanceDescription = CashAdvanceDescription::orderBy('name', 'ASC')->get();
        return CashAdvanceResource::collection($cashAdvanceDescription);
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
            'name' => 'required',
        ]);
        $cashAdvanceDescription = new CashAdvanceDescription();
        $cashAdvanceDescription->name = ucwords($request->name);
        if($cashAdvanceDescription->save()) {
            return new CashAdvanceResource($cashAdvanceDescription);
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
        $this->validate($request , [
            'name' => 'required',
        ]);
        $cashAdvanceDescription = CashAdvanceDescription::findOrFail($id);
        $cashAdvanceDescription->name = ucwords($request->name);
        if($cashAdvanceDescription->save()) {
            return new CashAdvanceResource($cashAdvanceDescription);
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
        $cashAdvanceDescription = CashAdvanceDescription::find($id);
        if($cashAdvanceDescription->delete()) {
            return new CashAdvanceResource($cashAdvanceDescription);
        }
    }
}
