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
	Route::post ( '/logout', 'HomeController@logout' );
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
	Route::get('chart/values/{device}/{label}', 'ChartController@showLabelX');
	Route::get('chart/labels/{device}', 'ChartController@getAllLabels');
	Route::post('label/get_labels', 'ChartController@get_labels');
	Route::post('label/get_labelbyID', 'ChartController@get_labelbyID');
	Route::post('dashboard', 'ChartController@dashboard');
	Route::post('label/device_detail', 'ChartController@device_detail');
	Route::post('update_appliances', 'ChartController@update_appliances');
	Route::post('alert_status_on', 'ChartController@alert_status_on');
	Route::post('alert_status_off', 'ChartController@alert_status_off');
	Route::post('update_label', 'ChartController@update_label');
	Route::post('get_streaming', 'ChartController@get_streaming');
	Route::post('update_streaming', 'ChartController@update_streaming');
	Route::post('get_history', 'ChartController@get_history');
	Route::post('clear_history', 'ChartController@clear_history');
});
