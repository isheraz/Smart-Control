<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Sheraz Ahmed',
                'email' => 'sherazahmdd@gmail.com',
                'password' => Hash::make('asdf1234'),
                'has_questions' => 0,
                'remember_token' => NULL,
                'created_at' => '2019-01-24 10:00:41',
                'updated_at' => '2019-01-24 10:00:41',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Fawad Butt',
                'email' => 'fawad@gmail.com',
                'password' => '$2y$10$5GD9VmEeBLpx.E8nKvi.deNU5DzU87yFQQvFnai3EjQdGUai6efNG',
                'has_questions' => 0,
                'remember_token' => NULL,
                'created_at' => '2019-01-24 10:00:41',
                'updated_at' => '2019-01-24 10:00:41',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'User X',
                'email' => 'user@x.com',
                'password' => '$2y$10$EM05FpnjxrXl0MUq1evuZeL0e2AENIRUgmJ2AeZb4lxai43/IrHly',
                'has_questions' => 0,
                'remember_token' => NULL,
                'created_at' => '2019-01-24 10:00:41',
                'updated_at' => '2019-01-24 10:00:41',
            ),
        ));
        
        
    }
}