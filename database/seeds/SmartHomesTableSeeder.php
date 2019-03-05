<?php

use App\Node;
use App\SmartHome;
use App\SmartHomeMeta;
use Illuminate\Database\Seeder;

class SmartHomesTableSeeder extends Seeder {
	
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run() {
		$faker      = Faker\Factory::create();
		
		for ( $i = 0; $i < 15; $i ++ ) {
			SmartHome::create( [
				'batch'         => $faker->bothify( 'SmartHome-?????###' ),
				'serial'        => $faker->bothify( '##??#?###' ),
				'user_id'       => $faker->randomElement( [ 1, 2, 3 ] ),
				'location_name' => $faker->randomElement( [
					'My Room',
					'Shower',
					'Bedroom',
					'Kitchen',
					'Computer Lab'
				] ),
				'location_icon' => $faker->randomElement( [
					'fa-bed',
					'fa-shower',
					'fa-cutlery',
					'fa-desktop',
					'fa-plug'
				] ),
			] );
		}
		
		
	}
}