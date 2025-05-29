@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Введіть номер телефону для входу'))

@section('auth_body')
    <form id="phone_form" action="{{ route('otp.send') }}" method="POST">
        @csrf
        <div class="input-group mb-3">
            <input type="text" name="phone" class="form-control" placeholder="Номер телефону" required autofocus>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-phone"></span>
                </div>
            </div>
        </div>
        @error('phone')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <button type="submit" class="btn btn-secondary btn-block">Відправити код</button>
    </form>
@endsection
<style>
    .hidden-element {
        display: none;
    }
</style>
@section('auth_footer')
    @if(session('error'))
        <div class="text-danger">{{ session('error') }}</div>
    @endif
@endsection
