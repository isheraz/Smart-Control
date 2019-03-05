<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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
}
