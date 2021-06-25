<?php

namespace App\Http\Controllers;

use App\Http\Resources\BreakSeederResource;
use App\Models\BreakSeeders;
use Illuminate\Http\Request;

class BreakSeedersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breakSeeders = BreakSeeders::all();
        return BreakSeederResource::collection($breakSeeders);
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
            'hours' => 'required',
        ]);

        $breakSeeders = new BreakSeeders();
        $breakSeeders->hours = ucwords($request->name);
        if($breakSeeders->save()) {
            return new BreakSeederResource($breakSeeders);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BreakSeeders  $breakSeeders
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $breakSeeders = BreakSeeders::findOrFail($id);
         if($breakSeeders) {
             return new BreakSeederResource($breakSeeders);
         }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BreakSeeders  $breakSeeders
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
     * @param  \App\Models\BreakSeeders  $breakSeeders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request , [
            'hours' => 'required',
        ]);

        $breakSeeders = new BreakSeeders();
        $breakSeeders->hours = ucwords($request->name);
        if($breakSeeders->save()) {
            return new BreakSeederResource($breakSeeders);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BreakSeeders  $breakSeeders
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $breakSeeders = BreakSeeders::find($id);
        if($breakSeeders->delete()) {
            return new BreakSeederResource($breakSeeders);
        }
    }
}
