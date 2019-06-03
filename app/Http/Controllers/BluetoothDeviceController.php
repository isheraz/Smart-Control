<?php

namespace App\Http\Controllers;

use App\BluetoothDevice;
use App\BTDeviceGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Faker\Factory;

class BluetoothDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $bluetooth  =   BTDeviceGroup::all();
        $devices    =   BluetoothDevice::all();

        return view(
            'bluetooth.index', [
                'bluetooth' => $bluetooth,
                'devices' => $devices
            ]);
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $device = BluetoothDevice::where('mac_address', $request->macAddress)->get()->first();
        if(empty($device)){
            $faker = Factory::create();
            $device = \App\BluetoothDevice::create([
                'mac_address' => $request->macAddress,
                'mode' => $request->mode,
                'uuid' => $faker->uuid
            ]);

            $this->sortDevices($device);
            return response()->json(["message"=>"Device Added Successfully"]);
        }else{
            return response()->json(["message"=>"Device Already exists"]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BluetoothDevice $bluetoothDevice
     * @return \Illuminate\Http\Response
     */
    public function show(BluetoothDevice $bluetoothDevice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BluetoothDevice $bluetoothDevice
     * @return \Illuminate\Http\Response
     */
    public function edit(BluetoothDevice $bluetoothDevice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\BluetoothDevice $bluetoothDevice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BluetoothDevice $bluetoothDevice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BluetoothDevice $bluetoothDevice
     * @return \Illuminate\Http\Response
     */
    public function destroy(BluetoothDevice $bluetoothDevice)
    {
        //
    }

    /**
     * Reset BluetoothDevices and groups
     */
    public function reset()
    {
        DB::table('bluetooth_devices')->truncate();
        DB::table('b_t_device_groups')->truncate();

        return redirect()->back()->with(['status' => true, 'alert-type' => 'danger', 'message' => 'Removed all devices and groups']);
    }

    public function master(Request $request){

        $deviceGroup = BTDeviceGroup::where( 'master_mac_address', "$request->macAddress")->get()->first();
        $device= BluetoothDevice::where( 'mac_address', "$request->macAddress")->get()->first();
        if(isset($deviceGroup)){
            $deviceGroup['uuid'] = $device->uuid;
            return response()->json($deviceGroup);
        }else{
            return response()->json(["message"=>"The mac_address does not belong to a master device or does not exist"]);
        }
    }

    public function sortDevices($d1)
    {

        if ($d1->mode == "M") {
            $group = \DB::table('b_t_device_groups')->where('master_mac_address', 'LIKE', $d1->mac_address)->get()->first();
            if (empty($group)) {
                \DB::table('b_t_device_groups')->insert([
                    'master_mac_address' => $d1->mac_address,
                    'devices' => json_encode([$d1->id])
                ]);
            } else {
                echo 'Group with Master Already Exists';
            }
        } else {
            $group = \DB::table('b_t_device_groups')->select('*')->whereRaw('JSON_LENGTH(devices)<3')->get()->first();
            if (isset($group)) {

                $devices = json_decode($group->devices);
                array_push($devices, $d1->id);
                $devices = json_encode($devices);
                \DB::table('b_t_device_groups')->where('master_mac_address', $group->master_mac_address)
                    ->update(['devices' => $devices]);
            }
        }
    }
}
