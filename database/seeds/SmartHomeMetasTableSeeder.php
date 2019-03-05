<?php

use App\Appliance;
use App\SmartHomeMeta;
use Illuminate\Database\Seeder;

class SmartHomeMetasTableSeeder extends Seeder {
	
	/**
	 * Auto generated seed file
	 *
	 * @return void
	 */
	public function run() {
		
		$appliances = [
			"Bulb",
			"TV",
			"Fan"
		];
		$icons      = [ "fa-lightbulb-o", "fa-superpowers", "fa-sign-in" ];
		
		$faker = Faker\Factory::create();
		for ( $i = 0; $i < 100; $i ++ ) {
			SmartHomeMeta::create( [
				'smart_home_id' => $faker->numberBetween( 1, 15 ),
				'key'           => $faker->randomElement( [ 'voltages', 'temperature', 'watts', 'units' ] ),
				'value'         => $faker->numberBetween( 1, 220 ),
			] );
			
			Appliance::create( [
				'smart_home_id' => $faker->numberBetween( 1, 15 ),
				'name'          => $faker->randomElement( $appliances ),
				'state'         => $faker->boolean(),
				'icon'          => $faker->randomElement( $icons )
			] );
		}
	}
}