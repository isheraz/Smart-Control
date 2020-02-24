<?php

namespace App\Http\Controllers;

use App\Chart;
use App\SmartHome;
use Illuminate\Http\Request;

class ChartController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store($device_id, Request $request)
    {
        if (!Chart::where('device_id', $device_id)->get()->first()) {
            return response()->json(Chart::create([
                'title' => $request->title,
                'type' => /* $request->type or  */json_encode('line'),
                'x_axis' =>/*  $request->x_axis or */ json_encode('timestamp'),
                'y_axis' => json_encode($request->y_axis),
                'device_id' => $device_id
            ]));
        }

        return response()->json(['error' => 'chart for device already exists'], 401);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function show(SmartHome $device)
    {
        return $device->charts();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function showX(SmartHome $device)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Chart $chart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function destroy(Chart $chart)
    {
        //
    }
}
