<?php

namespace App\Console\Commands;

use App\SmartHome;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Devices extends Command {
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'devices:disconnect';
	
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Change status of all devices to disconnected';
	
	/**
	 * Create a new command instance.
	 */
	public function __construct() {
		parent::__construct();
	}
	
	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle() {
		$this->disconnect();
	}
	
	/**
	 * Schedule the function to change all devices to disconnected
	 */
	public function disconnect() {
		$devices = SmartHome::all();
		
		foreach ( $devices as $device ) {
			if ( $device->connection ) {
				$device->timestamps = false;
				$device->connection = false;
				$device->save();
			}
		}
		
		return true;
	}
}