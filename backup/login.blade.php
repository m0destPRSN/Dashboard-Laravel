@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Login with Phone Number'))

@section('auth_body')
    {{-- Message container for errors and info --}}
    <div id="login_message" class="mb-3 text-danger"></div>

    <form id="login_form" action="{{ route('login') }}" method="post">
        @csrf

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

        {{-- Request OTP button --}}
        <div class="mb-3">
            <button type="button" id="request_otp_button" class="btn btn-secondary btn-block">Вхід</button>
        </div>

        {{-- OTP fields --}}
        <div class="mb-3" id="otp_section" style="display: none;">
            <div class="d-flex justify-content-between" style="gap: 5px;">
                @for ($i = 1; $i <= 6; $i++)
                    <input type="text" maxlength="1" class="form-control otp-input text-center" style="width: 40px; font-size: 1.5rem;" autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" required>
                @endfor
                <input type="hidden" name="otp" id="otp_input">
            </div>
        </div>
        @error('otp')
        <div class="text-danger">{{ $message }}</div>
        @enderror

        {{-- Submit button --}}
        <div class="row" id="submit_section" style="display: none;">
            <div class="col-12">
                <button id="button_submit" type="submit" class="btn btn-primary btn-block">{{ __('Підтвердити') }}</button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <script>
        function showMessage(msg, isError = true) {
            const el = document.getElementById('login_message');
            el.textContent = msg;
            el.className = isError ? 'mb-3 text-danger' : 'mb-3 text-success';
        }

        document.getElementById('request_otp_button').addEventListener('click', function () {
            const phone = document.getElementById('phone_input').value;
            const phoneRegex = /^\+?[0-9]{10,15}$/;
            if (!phoneRegex.test(phone)) {
                showMessage('Please enter a valid phone number.');
                return;
            }

            fetch('{{ route('otp.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ phone })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        showMessage(data.message, false);
                        document.getElementById('phone_input').closest('.input-group').style.display = 'none';
                        document.getElementById('request_otp_button').parentElement.style.display = 'none';
                        document.getElementById('otp_section').style.display = 'block';
                        document.getElementById('submit_section').style.display = 'block';
                        // Focus first OTP input
                        const otpInputs = document.querySelectorAll('.otp-input');
                        if (otpInputs.length) otpInputs[0].focus();
                    } else if (data.error) {
                        showMessage(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Failed to send OTP. Please try again.');
                });
        });

        // OTP input logic
        document.addEventListener('DOMContentLoaded', function () {
            const otpInputs = document.querySelectorAll('.otp-input');
            otpInputs.forEach((input, idx) => {
                input.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9]/g, '');
                    if (this.value.length === 1 && idx < otpInputs.length - 1) {
                        otpInputs[idx + 1].focus();
                    }
                });
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && !this.value && idx > 0) {
                        otpInputs[idx - 1].focus();
                    }
                });
                input.addEventListener('paste', function(e) {
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    if (/^\d{6}$/.test(paste)) {
                        for (let i = 0; i < 6; i++) {
                            otpInputs[i].value = paste[i];
                        }
                        otpInputs[5].focus();
                        e.preventDefault();
                    }
                });
            });

            document.getElementById('login_form').addEventListener('submit', function(e) {
                const otp = Array.from(otpInputs).map(i => i.value).join('');
                document.getElementById('otp_input').value = otp;
            });
        });
    </script>
@endsection
