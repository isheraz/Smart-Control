@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Reset Password') }}</div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <input id="email" type="hidden" class="form-control" name="email" value="{{
                                    $user->email }}">

                            <input id="email_id" type="hidden" class="form-control" name="user_id" value="{{
                                    $user->id }}">

                            <input id="question" type="hidden" class="form-control" name="question" value="{{
                                    $questions->question }}">

                            <div class="form-group row">
                                <label for="email" class="col-md-12 col-form-label text-center font-weight-bold">{{ __
                                ($questions->question) }}</label>

                                <small class="text-center col-md-12">Type your answer below</small>

                                <div class="col-md-12">
                                    <input type="text" name="answer" placeholder="Your Answer here"
                                           class="form-control">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Send Password Reset Link') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
