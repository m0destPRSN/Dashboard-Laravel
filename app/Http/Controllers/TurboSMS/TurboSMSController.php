<?php

namespace App\Http\Controllers\TurboSMS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use SoapClient;

class TurboSMSController extends Controller
{
    protected $client;

    public function __construct()
    {
        // Initialize the SOAP client
        $this->client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');
    }

    /**
     * Send or resend OTP to the given phone number.
     * Accepts POST requests to /otp/send and /otp/resend.
     */
    public function sendOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
        ]);

        $phone = $request->phone;
        $otp = rand(100000, 999999);
        //$otp = '123456'; // For testing, use a fixed OTP

        // Store OTP in cache for 5 minutes
        Cache::put('otp_' . $phone, $otp, now()->addMinutes(5));

        try {
            $auth = [
                'login' => config('services.turbosms.login'),
                'password' => config('services.turbosms.password'),
            ];

            $authResult = $this->client->Auth($auth);
            if ($authResult->AuthResult !== 'Вы успешно авторизировались') {
                return response()->json(['error' => 'Authentication failed'], 401);
            }

            $sms = [
                'sender' => 'Kolo',
                'destination' => '+38' . $phone,
                'text' => "Your OTP is: $otp",
            ];

            $this->client->SendSMS($sms);

            // If this is an AJAX request (resend), return JSON
            if ($request->expectsJson()) {
                return response()->json(['message' => 'OTP sent successfully']);
            }

            // If this is the initial send, redirect to OTP form
            return redirect()->route('otp.form', ['phone' => $phone]);
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->with('error', 'Failed to send OTP: ' . $e->getMessage());
        }
    }

    /**
     * Verify the OTP for the given phone number.
     * Accepts POST requests to /otp/verify.
     */
    public function verifyOTP(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        $phone = $request->phone;
        $otp = $request->otp;

        $cachedOtp = Cache::get('otp_' . $phone);

        if ($cachedOtp && $cachedOtp == $otp) {
            Cache::forget('otp_' . $phone);
            return response()->json(['message' => 'OTP verified successfully']);
        }

        return response()->json(['error' => 'Invalid or expired OTP'], 400);
    }
}
