<?php

namespace App\Http\Controllers;

use App\Appliance;
use App\Node;
use App\RoomUtil;
use App\SmartHome;
use function GuzzleHttp\Promise\all;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Karriere\JsonDecoder\JsonDecoder;
use Mockery\Exception;

class SmartHomeController extends Controller {
	
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct() {
		$this->middleware( 'auth',
			[
				'except' => [
					'alive',
					'disconnect',
					'devices',
					'update_device',
					'update_node',
					'kill',
					'units'
				]
			]
		);
	}
	
	/**
	 * show all devices
	 */
	public function devices($user_id) {
		$devices = SmartHome::all()->where('user_id', '=', $user_id);
		$devices_appended =[];
		foreach ($devices as $device){
			$device->nodes = (response()->json($device->nodes))->original;
			$device->device_metas = (response()->json($device->device_metas))->original;
			array_push($devices_appended,$device);
		}
		
		return response()->json( $devices_appended );
	}
	
	/**
	 * Send units data
	 */
	public function units( $serial ) {
		$device = SmartHome::where( 'serial', '=', $serial )->get()->first();
		
		return response()->json( [ "units" => $device->units ] );
	}
	
	/**
	 * Test function to do any sort of testing
	 */
	public function test() {
//		dd( Auth::user() );
	}
	
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		$user_id = Auth::user()->id;
		$devices      = SmartHome::all()->where('user_id', '=', $user_id);
		$connected    = count( SmartHome::where( 'connection', '=', '1' )->where('user_id', '=', $user_id)->get() );
		$disconnected = count( SmartHome::where( 'connection', '=', '0' )->where('user_id', '=', $user_id)->get() );
		
		return view( 'home', compact( 'devices', 'connected', 'disconnected' ) );
	}
	
	
	public function history(SmartHome $device ) {
		if($device->user_id == Auth::user()->id){
			return view( 'history', compact( 'device' ) );
		}return redirect()->back()->with([
			'status'=>1,
			'alert-type'=>'danger',
			'message'=>'you can only access your devices! No Hanky Panky!',
		]);
	}
	
	/**
	 * Show all attributes of a device
	 */
	public function show( SmartHome $device ) {
		
		if($device->user_id == Auth::user()->id){
			return view( 'device.show', compact( 'device' ) );
		}return redirect()->back()->with([
			'status'=>1,
			'alert-type'=>'danger',
			'message'=>'you can only access your devices! No Hanky Panky!',
		]);
	}
	
	/**
	 * Update device Icon and Name Only
	 */
	public function update( SmartHome $device, Request $request ) {
		$device->location_name = $request['location_name'];
		$device->location_icon = $request['location_icon'];
		$device->save();
		
		return redirect()->route( 'home' )->with( 'status', 'true' )->with( 'message', "Serial: $device->serial, $device->location_name  Updated" )
		                 ->with( 'alert-type', "success" );
	}
	
	public function update_units( $serial, Request $request ) {
//		dd($request);
		$device             = SmartHome::where( 'serial', '=', $serial )->get()->first();
		$device->timestamps = false;
		$device->units      = $request->units;
		
		$device->save();
		
		return back()->with( "message", "$device->serial units updated" );
		
	}
	
	public function update_device( $serial, Request $request ) {
		try {
			$device   = SmartHome::where( 'serial', '=', $serial )->get()->first();
			$jsondata = $request;
			
			$device->door_sensor = isset( $jsondata->door_sensor ) ? $jsondata->door_sensor : $device->door_sensor;
			$device->current     = isset( $jsondata->current ) ? $jsondata->current : $device->current;
			$device->temperature = isset( $jsondata->temperature ) ? $jsondata->temperature : $device->temperature;
			$device->voltages    = isset( $jsondata->voltages ) ? $jsondata->voltages : $device->voltages;
			$device->watts       = isset( $jsondata->watts ) ? $jsondata->watts : $device->watts;
			
			$device->save();
			
			$room                = new RoomUtil();
			$room->smart_home_id = $device->id;
			$room->door_sensor   = isset( $jsondata->door_sensor ) ? $jsondata->door_sensor : $room->door_sensor;
			$room->current       = isset( $jsondata->current ) ? $jsondata->current : $room->current;
			$room->temperature   = isset( $jsondata->temperature ) ? $jsondata->temperature : $room->temperature;
			$room->voltages      = isset( $jsondata->voltages ) ? $jsondata->voltages : $room->voltages;
			$room->watts         = isset( $jsondata->watts ) ? $jsondata->watts : $room->watts;
			
			$room->save();
		} catch ( Exception $e ) {
			return response()->json( [ "error" => "The Request was not processed values were empty" ] );
		}
		
		return response()->json( [ "message" => $device->serial . " Updated Successfully" ] );
	}
	
	
	/**
	 * API Function fetch data from all nodes and turn device status alive
	 */
	public function alive( $serial ) {
		$device             = SmartHome::where( 'serial', '=', $serial )->get()->first();
		$device->connection = true;
		$device->save();
		$nodes['device_metas'] = $device->device_metas;
		$nodes['nodes'] = $device->nodes;
		$device['user_name'] = $device->user->name;
		return $device;
	}
	
	/**
	 * Show Configuration to update Icon and Name
	 */
	public function configure( SmartHome $device ) {
		$selection = [
			"bed"       => "",
			"cutlery"   => "",
			"shower"    => "",
			"desktop"   => "",
			"laptop"    => "",
			"plug"      => "",
			"book"      => "",
			"briefcase" => "",
			"football"  => "",
			"home"      => "",
			"default"   => "",
		];
		switch ( $device->location_icon ) {
			
			case "fa-bed" :
				$selection["bed"] = "selected";
				break;
			case "fa-cutlery":
				$selection ["cutlery"] = "selected";
				break;
			case "fa-shower":
				$selection ["shower"] = "selected";
				break;
			case "fa-desktop":
				$selection ["desktop"] = "selected";
				break;
			case "fa-laptop":
				$selection ["laptop"] = "selected";
				break;
			case "fa-plug":
				$selection ["plug"] = "selected";
				break;
			case "fa-soccer-ball-o":
				$selection ["football"] = "selected";
				break;
			case "fa-home":
				$selection ["home"] = "selected";
				break;
			case "fa-book":
				$selection ["book"] = "selected";
				break;
			case "fa-briefcase":
				$selection ["briefcase"] = "selected";
				break;
			default :
				$selection ["default"] = "selected";
				break;
		}
		
		return view( 'device.configure', compact( 'device', 'selection' ) );
	}
	
	/**
	 * Show the settings for current node
	 */
	public function showNode( SmartHome $device, Appliance $node ) {
		$selection = [
			"bed"       => "",
			"cutlery"   => "",
			"shower"    => "",
			"desktop"   => "",
			"laptop"    => "",
			"plug"      => "",
			"book"      => "",
			"briefcase" => "",
			"football"  => "",
			"home"      => "",
			"default"   => "",
		];
		switch ( $node->icon ) {
			
			case "fa-bed" :
				$selection["bed"] = "selected";
				break;
			case "fa-cutlery":
				$selection ["cutlery"] = "selected";
				break;
			case "fa-shower":
				$selection ["shower"] = "selected";
				break;
			case "fa-desktop":
				$selection ["desktop"] = "selected";
				break;
			case "fa-laptop":
				$selection ["laptop"] = "selected";
				break;
			case "fa-plug":
				$selection ["plug"] = "selected";
				break;
			case "fa-soccer-ball-o":
				$selection ["football"] = "selected";
				break;
			case "fa-lightbulb-o":
				$selection ["lightbulb"] = "selected";
				break;
			case "fa-home":
				$selection ["home"] = "selected";
				break;
			case "fa-book":
				$selection ["book"] = "selected";
				break;
			case "fa-briefcase":
				$selection ["briefcase"] = "selected";
				break;
			default :
				$selection ["default"] = "selected";
				break;
		}
		
		return view( 'device.node', compact( 'device', 'node', 'selection' ) );
	}
	
	/**
	 * Function updates and stores the state of the node on a device.
	 *
	 * */
	public function updateNode( SmartHome $device, Request $request ) {
		
		$node = Appliance::where('id','=',$request->node)->get()->first();
		$node->state = !$node->state;
		$node->save();
		
		
		return [ "message" => "$node->name Updated" ];
	}
	
	public function delete(SmartHome $device){
		$device->delete();
		return redirect()->back()->with([
			'status'=> true,
			'alert-type'=>'danger',
			'message' =>'Device Deleted Successfully'
		]);
	}
	
	public function update_node( Appliance $serial, Request $request ) {
		try {
			$node_state = $request['state'] == "true" ? true : false;
			$serial->update([
				'state'=>$node_state
			]);
			$serial->save();
		} catch ( Exception $e ) {
			return response()->json( [ "error" => "The Request was not processed values were empty" ] );
		}
		
		return [ "message" => "$serial->id is Updated" ];
	}
	
	/*Deletes a Node*/
	public function deleteNode( SmartHome $device, Appliance $node ) {
		try {
			$node->delete();
		} catch ( Exception $e ) {
			return response()->json( [ "error" => "The Request was not processed values were empty" ] );
		}
		
		return redirect()->back()->with([
			'status'=> true,
			'alert-type'=>'danger',
			'message' =>'Appliance Deleted Successfully'
		]);
	}
	
	
	/**
	 * Function updates and stores the state of the node on a device.
	 *
	 * */
	public function kill( $serial ) {
		
		$device = SmartHome::where( 'serial', '=', $serial )->get()->first();
		
		$jsonDecoder        = new JsonDecoder();
		$nodes              = $device->io_nodes;
		$device->timestamps = false;
		
		$nodes          = $jsonDecoder->decodeMultiple( $nodes, Node::class );
		$nodes_modified = [];
		foreach ( $nodes as $node ) {
			$node->state = false;
			array_push( $nodes_modified, $node );
		}
		
		$device->io_nodes = json_encode( $nodes_modified );
		$device->save();
		
		return [ "message" => "$device->serial Turned off completely" ];
	}
	
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create() {
		return view( 'device.create' );
	}
	
	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function store( Request $request ) {
		$faker = \Faker\Factory::create();
		SmartHome::create( [
			'batch'         => $faker->bothify( 'SmartHome-?????###' ),
			'serial'        => $faker->bothify( '##??#?###' ),
			'user_id'       => auth()->id(),
			'token'         => '',
			'current'       => 0,
			'voltages'      => 0,
			'watts'         => 0,
			'units'         => 0,
			'door_sensor'   => false,
			'connection'    => false,
			'io_nodes'      => 0,
			'location_name' => $request->location_name,
			'location_icon' => $request->location_icon,
			'temperature'   => 0
		] );
		
		return redirect()->route( 'home' )->with( [ 'status'     => true,
		                                            'alert-type' => 'success',
		                                            'message'    => 'New Device Create'
		] );
	}
}
