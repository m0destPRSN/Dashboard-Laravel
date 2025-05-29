@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Admin Login'))

@section('auth_body')
    <form id="admin_login_form" action="{{ route('admin.login') }}" method="post">
        @csrf

        {{-- Email field --}}
        <div class="input-group mb-3">
            <input type="email" name="email" id="email_input" class="form-control" placeholder="Enter your email" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope"></span>
                </div>
            </div>
        </div>
        @error('email')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        {{-- Password field --}}
        <div class="input-group mb-3">
            <input type="password" name="password" id="password_input" class="form-control" placeholder="Enter your password" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                </div>
            </div>
        </div>
        @error('password')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        {{-- Submit button --}}
        <div class="row">
            <div class="col-12">
                <button id="button_submit" type="submit" class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
            </div>
        </div>
    </form>
@endsection
<style>
    .hidden-element {
        display: none;
    }
</style>
