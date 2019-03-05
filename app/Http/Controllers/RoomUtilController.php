<?php

namespace App\Http\Controllers;

use App\SmartHome;
use Illuminate\Http\Request;

class RoomUtilController extends Controller
{
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct() {
		$this->middleware( 'auth',
			[
				'except' => []
			]
		);
	}
    function index(SmartHome $device){
    	$history = $device->utils;
    	return view('history', compact('history'));
    }
}
