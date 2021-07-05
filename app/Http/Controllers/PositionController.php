<?php

namespace App\Http\Controllers;

use App\Http\Resources\PositionResource;
use App\Models\Location;
use App\Models\Position;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $position = Position::orderBy('name', 'ASC')->get();
        return PositionResource::collection($position);
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
            'location' => 'required'
        ]);
        $position = new Position();
        $position->name = ucwords($request->name);
        $position->location_id = $request->location;
        if($position->save()) {
            return new PositionResource($position);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $position = Position::findOrFail($id);
        if($position) {
            return new PositionResource($position);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request , [
            'name' => 'required',
            'location' => 'required'
        ]);
        
        $position = Position::find($id);
        $position->name = ucwords($request->name);
        $position->location_id = $request->location;
        if($position->save()) {
            return new PositionResource($position);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $position = Position::find($id);
        if($position->delete()) {
            return new PositionResource($position);
        }
    }
}
