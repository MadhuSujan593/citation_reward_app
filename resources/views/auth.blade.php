@extends('layouts.app')

@section('content')
    <div id="register-page" class="page active">
        @include('auth.register')
    </div>

    <div id="login-page" class="page">
        @include('auth.login')
    </div>

    <div id="forgot-page" class="page">
        @include('auth.forgot')
    </div>
@endsection
