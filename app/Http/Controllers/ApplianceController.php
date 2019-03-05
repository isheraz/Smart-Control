<?php

namespace App\Http\Controllers;

use App\Appliance;
use Illuminate\Http\Request;

class ApplianceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
	    $this->middleware( 'auth',
		    [
			    'except' => []
		    ]
	    );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        return view('device.create-appliance', compact('id'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
    	Appliance::create([
    		'smart_home_id'=> $id,
		    'name'=>$request->location_name,
		    'state'=>false,
		    'icon'=>$request->location_icon
	    ]);
    	
        return redirect()->route('device',[$id])->with([
	        'status'=> true,
	        'alert-type'=>'success',
	        'message' =>'Appliance Created Successfully'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Appliance  $appliance
     * @return \Illuminate\Http\Response
     */
    public function show(Appliance $appliance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Appliance  $appliance
     * @return \Illuminate\Http\Response
     */
    public function edit(Appliance $appliance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Appliance  $appliance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Appliance $appliance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Appliance  $appliance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Appliance $appliance)
    {
        //
    }
}
