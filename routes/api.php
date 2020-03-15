<?php

use App\Http\Controllers\ChartController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group( [ 'middleware' => [ 'api' ] ], function () {

//	Route::post ( '/register', 'HomeController@registration' );
	Route::post ( '/login', 'HomeController@authenticate' );
	
	// Route to check if connection is alive
	Route::get( 'alive/{serial}', 'SmartHomeController@alive' );
	Route::post( 'update/meta/{serial}', 'SmartHomeMetaController@update' );
	
	Route::get( 'units/{serial}', 'SmartHomeController@units' );
	Route::get( 'kill/{serial}', 'SmartHomeController@kill' );
	Route::post( 'update/{serial}', 'SmartHomeController@update_device' );
	Route::post( 'update_node/{serial}', 'SmartHomeController@update_node' );
	
	
	// to get all devices intended for testing
	Route::get( 'devices/{user_id}', 'SmartHomeController@devices' );
	// Add Data to Bluetooth Group
    Route::post('bluetooth/add', 'BluetoothDeviceController@store');
	Route::post('bluetooth/group', 'BluetoothDeviceController@master');


	Route::post('chart/update/{device}', 'ChartController@update');
	Route::get('chart/values/{device}', 'ChartController@showX');
} );
