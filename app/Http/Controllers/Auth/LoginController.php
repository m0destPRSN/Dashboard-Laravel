<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|digits:10',
            'otp' => 'required|digits:6',
        ]);

        $phone = $request->phone;
        $otp = $request->otp;

        // Verify OTP
        $cachedOtp = Cache::get('otp_' . $phone);
        if (!$cachedOtp || $cachedOtp != $otp) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP']);
        }
        Cache::forget('otp_' . $phone);

        // Find or create user
        $user = User::where('phone', $phone)->first();
        if (!$user) {
            $user = User::create([
                'phone' => $phone,
                'name' => 'User_' . $phone,
                'role' => 'user', // default role
            ]);
        }

        Auth::login($user);

        // Check if first_name or second_name is empty
        if (empty($user->first_name) || empty($user->second_name)) {
            return redirect()->route('profile.complete');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect('/');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
}
