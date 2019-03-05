<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartHomeMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('smart_home_metas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('smart_home_id');
            $table->string('key');
            $table->string('value');
	        $table->foreign( 'smart_home_id' )->references( 'id' )->on( 'smart_homes' );
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
        Schema::dropIfExists('smart_home_metas');
    }
}
