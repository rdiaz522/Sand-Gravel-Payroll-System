<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimeLogsResource;
use App\Models\Timelogs;
use Illuminate\Http\Request;

class TimelogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $this->validate($request, [
            'employee_id' => 'required',
            'position_id' => 'required',
            'daily_rate' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
            'break_time' => 'required',
            'total_hours' => 'required',
            'log_date' => 'required',
        ]);

        $timelogs = new Timelogs();
        $timelogs->employee_id = $request->employee_id;
        $timelogs->position_id = $request->position_id;
        $timelogs->daily_rate = $request->daily_rate;
        $timelogs->time_in = $request->time_in;
        $timelogs->time_out = $request->time_out;
        $timelogs->break_time = $request->break_time;
        $timelogs->total_hours = $request->total_hours;
        $timelogs->log_date = date('Y-m-d', strtotime($request->log_date));
        if($timelogs->save()) {
            return new TimeLogsResource($timelogs);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Timelogs  $timelogs
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Timelogs  $timelogs
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
     * @param  \App\Models\Timelogs  $timelogs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timelogs  $timelogs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
