<?php

namespace App\Http\Controllers;

use App\Chart;
use App\ChartData;
use App\SmartHome;
use DateTime;
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
            $chart = Chart::create([
                'title' => $request->title,
                'type' => /* $request->type or  */ 'line',
                'x_axis' =>/*  $request->x_axis or */ 'timestamp',
                'y_axis' => $request->y_axis,
                'device_id' => $device_id
            ]);
            return response()->json(['message' => 'chart for device created successfully'], 200);
        }

        return response()->json(['message' => 'chart for device already exists'], 401);
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
    public function showX($device)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with('chartValues')->first();
        // dd(ChartData::all());
        return response()->json($chart);
    }

    public function showLabelX($device, $label)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with(['chartValues' => function ($q) use ($label) {
            $q->where('chart_label','LIKE', "%$label%");
        }])->get();
        return response()->json($chart);
    }

    public function getAllLabels($device)
    {
        $device = SmartHome::where('serial', $device)->get()->first();
        $chart = $device->chart()->with(['chartValues' => function ($q) {
           
        }])->get()->first()->chartValues->groupBy('chart_label');
        // dd($chart->first()->chartValues->unique('chart_label'));
        return response()->json($chart);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Chart  $chart
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $device)
    {
        $device = SmartHome::where('serial', $device)->first();
        // dd($device);
        $chart = $device->chart->with('chartValues')->first();
        $res = ChartData::insert([
            'x' => new DateTime(now()),
            'y' => $request->y,
            'chart_label' => $request->label,
            'chart_id' => $chart->id
        ]);

        return response()->json(["value_added" => $res]);
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
