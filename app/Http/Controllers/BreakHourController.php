<?php

namespace App\Http\Controllers;

use App\Http\Resources\BreakHourResource;
use App\Models\BreakHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
class BreakHourController extends Controller
{
    
    protected $DATENOW = '';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breakHour = BreakHour::all();
        if($breakHour) {
            return BreakHourResource::collection($breakHour);
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

    public function DateNow()
    {
        $carbon = Carbon::now('Asia/Manila')->format('Y-m-d');
        $this->DATENOW = $carbon;
    } 
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->DateNow();

        $this->validate($request , [
            'name' => 'required|unique:break_hours',
        ]);
        DB::table('break_hours')->insert([
            'name' =>  $request->name,
            'created_at' => $this->DATENOW,
            'updated_at' => $this->DATENOW
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BreakHour  $breakHour
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BreakHour  $breakHour
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
     * @param  \App\Models\BreakHour  $breakHour
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request , [
            'name' => 'required|unique:break_hours',
        ]);

        $breakHour = BreakHour::findOrFail($id);
        $breakHour->name = $request->name;
        if($breakHour) {
            return new BreakHourResource($breakHour);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BreakHour  $breakHour
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $breakHour = BreakHour::find($id);
        if($breakHour->delete()) {
            return new BreakHourResource($breakHour);
        }
    }
}
