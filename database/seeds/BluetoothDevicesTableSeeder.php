<?php

use Illuminate\Database\Seeder;
use Faker\Factory;

class BluetoothDevicesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('bluetooth_devices')->delete();
        \DB::table('b_t_device_groups')->delete();

       $faker =  Faker\Factory::create();

       for($i =0; $i<9;$i++) {
          $device = \App\BluetoothDevice::create([
               'name' => $faker->name('female'),
               'mac_address' => $faker->macAddress,
               'mode' => $faker->randomElement(['M', 'S']),
               'uuid' => $faker->uuid
           ]);

           $this->sortDevices($device);
       }
        
    }

    public function sortDevices($d1){

        if($d1->mode == "M"){
           $group =  \DB::table('b_t_device_groups')->where('master_mac_address', 'LIKE', $d1->mac_address)->get()->first();
            if(empty($group)){
                \DB::table('b_t_device_groups')->insert([
                    'master_mac_address' => $d1->mac_address,
                    'devices' => json_encode([$d1->id])
                ]);
            }else{
                echo 'Group with Master Already Exists';
            }
        }else {
            $group =  \DB::table('b_t_device_groups')->select('*')->whereRaw('JSON_LENGTH(devices)<3')->get()->first();
            if(isset($group)){

            $devices = json_decode($group->devices);
            array_push($devices, $d1->id);
            $devices = json_encode($devices);
            \DB::table('b_t_device_groups')->where('master_mac_address', $group->master_mac_address)
                ->update(['devices' => $devices]);
            }
        }
    }
}