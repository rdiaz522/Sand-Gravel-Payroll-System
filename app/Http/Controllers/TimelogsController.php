<?php

namespace App\Http\Controllers;

use App\Http\Resources\TimeLogsResource;
use App\Models\Position;
use App\Models\Timelogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TimelogsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $timelogs = Timelogs::orderBy('id', 'DESC')->get();
        if($timelogs) {
            return TimeLogsResource::collection($timelogs);
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
            'position_id' => 'required',
            'daily_rate' => 'required|numeric',
            'time_in' => 'required',
            'break_time' => 'required',
            'time_out' => 'required',
            'total_hours' => 'required',
            'log_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $location = Position::where('id', $request->position_id)->select('location_id')->first();
            $departmentId = 0;
            
            if($location instanceof Position && $location->exists) {
                $departmentId = $location->location_id;
            }

            $timelogs = new Timelogs();
            $timelogs->employee_id = $request->employee_id;
            $timelogs->department_id = $departmentId;
            $timelogs->daily_rate = (float)$request->daily_rate;
            $timelogs->time_in = $request->time_in;
            $timelogs->time_out = $request->time_out;
            $timelogs->break_time = $request->break_time;
            $timelogs->total_hours = $request->total_hours;
            $timelogs->log_date = date('Y-m-d', strtotime($request->log_date));
            $timelogs->log_date2 = date('Y-m-d', strtotime($request->log_date2));
            $timelogs->total_pay = (float)$request->total_hours * ((float)$request->daily_rate / 8);
            if($timelogs->save()) {
                return new TimeLogsResource($timelogs);
            }
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
        $validator = Validator::make($request->toArray(), [
            'daily_rate' => 'required|numeric',
            'time_in' => 'required',
            'break_time' => 'required',
            'time_out' => 'required',
            'total_hours' => 'required',
            'log_date' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json($validator->messages()->first(), 400);
        } else {
            $location = Position::where('id', $request->position_id)->select('location_id')->first();
            $departmentId = 0;
            $timelogs = Timelogs::findOrFail($id);

            if($location instanceof Position && $location->exists) {
                $departmentId = $location->location_id;
                $timelogs->department_id = $departmentId;
            }

            $timelogs->daily_rate = (float)$request->daily_rate;
            $timelogs->time_in = $request->time_in;
            $timelogs->time_out = $request->time_out;
            $timelogs->break_time = $request->break_time;
            $timelogs->total_hours = $request->total_hours;
            $timelogs->log_date = date('Y-m-d', strtotime($request->log_date));
            $timelogs->log_date2 = date('Y-m-d', strtotime($request->log_date2));
            $timelogs->total_pay = (float)$request->total_hours * ((float)$request->daily_rate / 8);
            if($timelogs->save()) {
                return new TimeLogsResource($timelogs);
            }
        }

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Timelogs  $timelogs
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $timelogs = Timelogs::find($id);
        if ($timelogs->delete()) { 
            return new TimeLogsResource($timelogs);
        }
    }
}
