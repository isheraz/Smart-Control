<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get( '/', function () {
	return view( 'welcome' );
} )->middleware('verified');

Auth::routes(['verify' => true]);

$this->put( 'password/email', 'Auth\ForgotPasswordController@showQuestion' )->name( 'password.question' );
$this->post( 'password/email', 'Auth\ForgotPasswordController@validateQuestion' )->name( 'password.email' );

Route::get( '/test', 'SmartHomeController@test' );


Route::get( '/home', 'SmartHomeController@index' )->middleware('verified')->name( 'home' );

Route::get( '/disconnect', 'SmartHomeController@disconnect' );
//Device Routes
Route::get( '/configure/{device}', 'SmartHomeController@configure' )->name( 'configure' );
Route::get( '/delete/{device}', 'SmartHomeController@delete' )->name( 'delete_device' );

Route::get( '/device/{device}', 'SmartHomeController@show' )->name( 'device' );
Route::get( '/create/device', 'SmartHomeController@create' )->name( 'device_create' );
Route::post( '/create/device', 'SmartHomeController@store' )->name( 'device-create' );

Route::post( '/device/{device}', 'SmartHomeController@update' )->name( 'update_device' );
Route::post( '/units/{device}', 'SmartHomeController@update_units' )->name( 'update_units' );

// Node Routes
Route::get( '/appliance/create/{device}', 'ApplianceController@create' )->name( 'create-appliance' );
Route::post( '/appliance/create/{device}', 'ApplianceController@store' )->name( 'store-appliance' );
Route::get( '/node/{device}/{node}', 'SmartHomeController@showNode' )->name( 'show_node' );
Route::post( '/node/{device}', 'SmartHomeController@updateNode' )->name( 'update_node' );
Route::put( '/node/{device}', 'SmartHomeController@putNode' )->name( 'put_node' );
Route::get( '/delete_node/{device}/{node}', 'SmartHomeController@deleteNode' )->name( 'delete_node' );


// History Routes
Route::get( 'history/{device}', 'RoomUtilController@index' )->name( 'history' );


// Device Meta
Route::prefix('meta')->group(function () {
//	Route::get('/{key}/{device}','SmartHomeMetaController@key_value');
//	Route::get('/{device}','SmartHomeMetaController@index');
	Route::get('/delete/{smartHomeMeta}','SmartHomeMetaController@destroy');
	Route::post('/create','SmartHomeMetaController@store')->name('device-attribute');
	/*Route::get('/delete/{smartHomeMeta}',function (SmartHomeMeta $smartHomeMeta){
		return $smartHomeMeta;
	});*/
	
});

Route::prefix('bluetooth')->group(function () {
    Route::get('/', 'BluetoothDeviceController@index')->name('bt-group-list');
    Route::get('/reset','BluetoothDeviceController@reset')->name('bt-reset-devices');
    Route::get('/delete/{smartHomeMeta}','BluetoothDeviceController@destroy')->name('delete-bluetooth-device');
    Route::post('/create','BluetoothDeviceController@store')->name('create-bluetooth-device');
});

// Graph Routes
Route::prefix('graphs')->group(function(){
   Route::get('/{graph}', 'ChartController@show')->name('show-graph');
   Route::post('/{device}', 'ChartController@store')->name('store-graph');

});
Route::post('update_label', 'LabelController@update_label')->name('update_label');
   Route::post('store_label', 'LabelController@store_label')->name('store_label');
  Route::get('/label_delete/{label_id}','LabelController@label_delete');
 Route::post('update_manual_alert','LabelController@update_manual_alert')->name('update_manual_alert');
 
 Route::post('update_streaming', 'LabelController@update_streaming')->name('update_streaming');
 Route::post('store_streaming', 'LabelController@store_streaming')->name('store_streaming');
 Route::get('/streaming_delete/{label_id}','LabelController@streaming_delete');
 Route::post('view_history', 'LabelController@view_history')->name('view_history');
 Route::get('/clear_history/{history_id}','LabelController@clear_history');
  Route::post('update_limit','LabelController@update_limit')->name('update_limit');
 