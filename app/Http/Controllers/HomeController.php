<?php

namespace App\Http\Controllers;

use App\User;
use App\UserApp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
	protected function validator( array $data ) {
		return Validator::make( $data, [
			'name'     => 'required|string|max:255',
			'email'    => 'required|string|email|max:255|unique:users',
			'password' => 'required|string|min:6|confirmed',
		] );
	}
	
	function registration( Request $request ) {
		$this->validator( $request->all() );
		
		$user           = new User();
		$user->email    = $request->email;
		$user->password = Hash::make( $request->password );
		$user->save();
		
		return response()->json(
			[
				"registration" => true
			]
		);
		
	}
	
	public function authenticate( Request $request ) {
		
		$password = $request->password;
		
		$user = User::where( 'email', $request->email )
		            ->first();
		if ( ! empty( $user ) ) {
			$hashCheck = Hash::check( $password, $user->password );
		} else {
			return response()->json( [ 'login' => 'false' ] );
		}
		
		
		if ( ! empty( $user ) && $hashCheck == true ) {
			
			$stask = new UserApp;	
            $stask->user_id = $user->id;
            $stask->app_id =  isset($request->app_id) ?$request->app_id : 0 ;
            $stask->save();
            
			return response()->json(
				[
					"login"   => true,
					'user_id' => $user->id,
				]
			)->cookie( 'token', Hash::make( $user->password ), true );
		} else {
			return response()->json(
				[
					"login" => false,
					"user_id" => $user->id
				]
			);
		}
		
	}
	public function logout( Request $request ) {
	
	$deleted = DB::delete('delete from user_apps where user_id="'.$request->user_id.'" and app_id="'.$request->app_id.'"');
	return response()->json(["status" => "logout"]);
	}
}
