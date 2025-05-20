@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Login with Telegram'))

@section('auth_body')
    <div id="login_form">
        {{-- Phone number field --}}
        <div class="input-group mb-3">
            <input type="text" name="phone" id="phone_input" class="form-control" placeholder="Enter your phone number" required autofocus>
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
                <button id="button_submit" type="submit" class="btn btn-primary btn-block">{{ __('Sign In') }}</button>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-3 d-flex justify-content-center  align-items-center">
                <a href="/">Пропустити</a>
            </div>
        </div>

        <div id="errors" style="display: none;"></div>
    </div>
@endsection

@push('js')
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let tg = window.Telegram.WebApp;
            let errorsDiv = document.getElementById('errors');

            if (tg) {
                const user = tg.initDataUnsafe.user;
                if (user) {
                    const { first_name, last_name } = user;
                    const tg_id = user.id;
                    const tg_username = user.username;

                    const buttonSubmit = document.getElementById('button_submit');
                    buttonSubmit.addEventListener("click", function(event) {
                        event.preventDefault();
                        const phone = document.getElementById('phone_input').value.trim();

                        fetch("/telegram/login", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ phone, first_name, last_name, tg_username, tg_id })
                        })
                            .then(async response => {
                                errorsDiv.style.display='block'
                                let data = await response.json();
                                if (response.ok) {
                                    window.location.href = data.redirect;
                                } else {
                                    errorsDiv.innerText = data.message || "Unknown error";
                                }
                            })
                            .catch(error => {
                                errorsDiv.innerText = "Fetch error: " + error.message;
                            });
                    });
                } else {
                    errorsDiv.innerText = "User data is missing.";
                }
            } else {
                errorsDiv.innerText = "Telegram Web App is not available.";
            }
        });


    </script>
@endpush
