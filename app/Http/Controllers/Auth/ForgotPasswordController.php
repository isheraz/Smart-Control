<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Question;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller {
	/*
	|--------------------------------------------------------------------------
	| Password Reset Controller
	|--------------------------------------------------------------------------
	|
	| This controller is responsible for handling password reset emails and
	| includes a trait which assists in sending these notifications from
	| your application to your users. Feel free to explore this trait.
	|
	*/
	
	use SendsPasswordResetEmails;
	
	/**
	 * Create a new controller instance.
	 */
	public function __construct() {
		$this->middleware( 'guest' );
	}
	
	public function validateQuestion( Request $request ) {
		$question = Question::where( 'question', '=', $request->question )->where( 'answer', '=', $request->answer )->where( 'user_id', '=', $request->user_id )->get();
		if ( $question != null ) {
			$this->sendResetLinkEmail($request);
			return view('auth.login')->with( 'status', 'Please Check your email' );
		} else {
			return back()->with( 'status', 'Incorrect Answer. Answer is case sensitive' );
		}
	}
	
	public function showQuestion( Request $request ) {
		$user      = User::where( 'email', '=', $request->email )->get()->first();
		if($user == null){
			$error['email'] = "Email not found";
			return back()->with($error);
		}
		$questions = Question::inRandomOrder()->where( 'user_id', '=', $user->id )->get()->first();
		if( $questions == null){
			$error['email'] = "Question not set for this user";
			return back()->with($error);
		}
		return view( 'auth.passwords.question', compact( 'questions', 'user' ) );
	}
}
