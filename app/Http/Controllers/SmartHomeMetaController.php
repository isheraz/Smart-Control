<?php

namespace App\Http\Controllers;

use App\SmartHome;
use App\SmartHomeMeta;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SmartHomeMetaController extends Controller {
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct() {
		$this->middleware( 'auth',
			[
				'except' => [ 'update' ]
			]
		);
	}
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index( $device ) {
		$device = SmartHome::where( 'serial', '=', $device )->get()->first();
		
		return ( $device->device_metas );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		$smart_home_id = $request->device_id;
		$meta          = null;
		$validator     = Validator::make( $request->all(), [
			'key'       => [
				Rule::unique( 'smart_home_metas', 'key' )
				    ->where( function ( $query ) use ( $smart_home_id ) {
					    return $query->where( 'smart_home_id', $smart_home_id );
				    } ),
				"required"
			],
			'value'     => 'required',
			'device_id' => 'required',
		] );
		if ( $validator->fails() ) {
			return redirect()->back()
			                 ->with( [
				                 'status'     => true,
				                 'alert-type' => 'danger',
				                 'message'    => $validator->errors()
			                 ] )
			                 ->withErrors( $validator )
			                 ->withInput();
		} else {
			$meta = SmartHomeMeta::create( [
				'key'           => $request->key,
				'value'         => $request->value,
				'smart_home_id' => $request->device_id,
			] );
		}
		
		return redirect()->route( 'device', $request->device_id )->with( [
			'status'     => true,
			'alert-type' => 'success',
			'message'    => $meta->key . 'Created Successfully'
		] );
	}
	
	/**
	 * Display the specified resource.
	 *
	 * @param  \App\SmartHomeMeta $smartHomeMeta
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function show( SmartHomeMeta $smartHomeMeta ) {
		//
	}
	
	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  \App\SmartHomeMeta $smartHomeMeta
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function edit( SmartHomeMeta $smartHomeMeta ) {
		//
	}
	
	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 * @param  \App\SmartHomeMeta $serial
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function update( Request $request, $serial ) {
		$flag = false;
		$device = SmartHome::where( 'serial', '=', $serial )->get()->first();
		foreach ( $request->toArray() as $key => $requested ) {
			$flag = false;
			foreach ( $device->device_metas as $meta ) {
				if ( $meta->key === $requested['key'] ) {
					$meta->value = $requested['value'];
					$meta->save();
					$flag = true;
				}
			}
		}
		if($flag){
			return response()->json([
				"success"=>true,
				"message"=>"Values Updated Successfully"
			]);
		}
		return response()->json([
			"error"=>true,
			"message"=>"Values do not exist. keys are case sensitive"
		]);
	}
	
	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  \App\SmartHomeMeta $smartHomeMeta
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function destroy( SmartHomeMeta $smartHomeMeta ) {
		$smartHomeMeta->delete();
		
		return redirect()->back()->with( [
			'status'     => true,
			'alert-type' => 'danger',
			'message'    => $smartHomeMeta->key . ' Deleted Successfully'
		] );
	}
	
	function key_value( $key, $device ) {
		$device = SmartHome::where( 'serial', '=', $device )->get()->first();
		
		return ( $device->device_metas->where( 'key', '=', $key ) );
	}
}
