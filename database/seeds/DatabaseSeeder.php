<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$this->call( UsersTableSeeder::class );
		$this->call( SmartHomesTableSeeder::class );
	    $this->call(SmartHomeMetasTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        // $this->call(BluetoothDevicesTableSeeder::class);
    }
}
