<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\PasswordOtp;
use Carbon\Carbon;

class ForgotPasswordOtpController extends Controller
{
    // Step 1: Show Forgot Password Page
    public function showForgot()
    {
        return view('auth.forgot-password');
    }

    // Step 2: Send OTP
    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $otp = rand(100000, 999999);

        PasswordOtp::where('email', $request->email)->delete();

        PasswordOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // Send via email
        Mail::raw("Your OTP is: $otp (valid for 5 minutes).", function ($message) use ($request) {
            $message->to($request->email)->subject('Password Reset OTP');
        });

        session(['reset_email' => $request->email]);

        return redirect()->route('password.otp.form')->with('status', 'OTP sent to your email!');
    }

    // Step 3: Show OTP Page
    public function showOtpForm()
    {
        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');
        return view('auth.verify-otp', compact('email'));
    }

    // Step 4: Verify OTP
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|numeric',
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->where('expires_at', '>', now())
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
        }

        $record->update(['verified' => true]);
        session(['reset_email' => $request->email]);

        return redirect()->route('password.change.form');
    }

    // Step 5: Show Change Password Page
    public function showChangePassword()
    {
        $email = session('reset_email');
        if (!$email) return redirect()->route('password.request');
        return view('auth.change-password', compact('email'));
    }

    // Step 6: Change Password
    public function changePassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $record = PasswordOtp::where('email', $request->email)
            ->where('verified', true)
            ->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'OTP verification required.']);
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        $record->delete();
        session()->forget('reset_email');

        return redirect()->route('password.changed');
    }

    // Step 7: Success Page
    public function success()
    {
        return view('auth.password-changed');
    }
}
