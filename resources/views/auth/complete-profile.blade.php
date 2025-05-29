@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Введіть ваше ім\'я та прізвище'))

@section('auth_body')
    <form method="POST" action="{{ route('profile.complete.save') }}">
        @csrf

        {{-- First Name --}}
        <div class="input-group mb-3">
            <input id="first_name" name="first_name" class="form-control" placeholder="First Name" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        @error('first_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        {{-- Second Name --}}
        <div class="input-group mb-3">
            <input id="second_name" name="second_name" class="form-control" placeholder="Second Name" required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-user"></span>
                </div>
            </div>
        </div>
        @error('second_name')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        {{-- Submit button --}}
        <div class="row">
            <div class="col-12">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
            </div>
        </div>
    </form>
@endsection
<style>
    .hidden-element {
        display: none;
    }
</style>
