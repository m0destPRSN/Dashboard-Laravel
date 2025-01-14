@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Login with Phone Number'))

@section('auth_body')
    <form action="{{ route('login') }}" method="post">
        @csrf

        {{-- Phone number field --}}
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Enter your phone number" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
        </div>
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror


        {{-- Submit button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
            </div>
        </div>
    </form>
@endsection

