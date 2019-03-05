<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSmartHomesTable extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create( 'smart_homes', function ( Blueprint $table ) {
			$table->increments( 'id' );
			$table->string( 'serial' )->unique();
			$table->string( 'batch' );
			$table->integer( 'user_id' );
			$table->boolean( 'connection' )->default(false);
			$table->string( 'location_name' );
			$table->string( 'location_icon' );
			$table->timestamps();
			$table->foreign( 'user_id' )->references( 'id' )->on( 'users' );
		} );
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists( 'smart_homes' );
	}
}
