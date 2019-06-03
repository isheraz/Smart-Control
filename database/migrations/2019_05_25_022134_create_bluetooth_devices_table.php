<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBluetoothDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bluetooth_devices', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->text('mac_address');
            $table->enum('mode',['M','S']);
            $table->text('uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bluetooth_devices');
        Schema::dropIfExists('bluetooth_device_group');
    }
}
