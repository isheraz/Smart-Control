<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChartDatasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chart_datas', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('chart_id');
            $table->string('chart_label');
            $table->string('x');
            $table->string('y');
            $table->timestamps();
            $table->foreign('chart_id')->references('id')->on('charts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_datas');
    }
}
