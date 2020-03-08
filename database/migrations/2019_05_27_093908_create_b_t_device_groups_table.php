<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBTDeviceGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_t_device_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name')->nullable();
            $table->char('master_mac_address',17)->unique();
            $table->text('devices');
            $table->integer('allowed');
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
        Schema::dropIfExists('b_t_device_groups');
    }
}
