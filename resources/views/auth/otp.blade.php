@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('auth_header', __('Введіть код який прийшов на номер +38') . $phone)

@section('auth_body')
    <div id="otp_message" class="mb-0 text-danger"></div>
    <form id="otp_form" method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="phone" value="{{ $phone }}">
        <div class="mb-3" id="otp_section">
            <div class="d-flex justify-content-between" style="gap: 5px;">
                @for ($i = 1; $i <= 6; $i++)
                    <input type="text" maxlength="1" class="form-control otp-input text-center" style="width: 40px; font-size: 1.5rem;" autocomplete="one-time-code" inputmode="numeric" pattern="[0-9]*" required>
                @endfor
                <input type="hidden" name="otp" id="otp_input">
            </div>
            <div class="mb-2">Не прийшов код?</div>
            <button type="button" id="resend_otp_link" class="btn btn-link p-0 align-baseline">Відправити код ще раз</button>
        </div>
        @error('otp')
        <div class="text-danger">{{ $message }}</div>
        @enderror
        <div class="row mt-3">
            <div class="col-12">
                <button id="button_submit" type="submit" class="btn btn-primary btn-block">{{ __('Підтвердити') }}</button>
            </div>
        </div>
    </form>
@endsection

@section('auth_footer')
    <script>
        let resendTimer;
        const resendCooldown = 120; // seconds

        function showMessage(msg, isError = true) {
            const el = document.getElementById('otp_message');
            el.textContent = msg;
            el.className = isError ? 'mb-3 text-danger' : 'mb-3 text-success';
        }

        function startResendTimer() {
            const resendBtn = document.getElementById('resend_otp_link');
            let timeLeft = resendCooldown;
            resendBtn.disabled = true;
            resendBtn.textContent = `Відправити код ще раз (${timeLeft})`;

            resendTimer = setInterval(() => {
                timeLeft--;
                resendBtn.textContent = `Відправити код ще раз (${timeLeft})`;
                if (timeLeft <= 0) {
                    clearInterval(resendTimer);
                    resendBtn.disabled = false;
                    resendBtn.textContent = 'Відправити код ще раз';
                }
            }, 1000);
        }

        document.getElementById('resend_otp_link').addEventListener('click', function (e) {
            e.preventDefault();
            if (this.disabled) return;
            const phone = document.querySelector('input[name="phone"]').value;
            const phoneRegex = /^\+?[0-9]{10,15}$/;
            if (!phoneRegex.test(phone)) {
                showMessage('Please enter a valid phone number.');
                return;
            }

            fetch('{{ route('otp.resend') }}', {
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
                        // Optionally clear OTP fields
                        const otpInputs = document.querySelectorAll('.otp-input');
                        otpInputs.forEach(input => input.value = '');
                        if (otpInputs.length) otpInputs[0].focus();
                        startResendTimer();
                    } else if (data.error) {
                        showMessage(data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Failed to resend OTP. Please try again.');
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

            document.getElementById('otp_form').addEventListener('submit', function(e) {
                const otp = Array.from(otpInputs).map(i => i.value).join('');
                document.getElementById('otp_input').value = otp;
            });

            // Start timer on load
            startResendTimer();
        });
    </script>
@endsection
<style>
    .hidden-element {
        display: none;
    }
</style>
