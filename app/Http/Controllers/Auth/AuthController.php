<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'password.required' => 'Password is required.',
        ]);

        if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/admin/dashboard');
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/student/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()->min(8)->letters()->numbers()->symbols()],
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $user->profile()->create();

        $verificationCode = strtoupper(Str::random(6));
        $user->verification_code = $verificationCode;
        $user->verification_expires_at = now()->addMinutes(30);
        $user->save();

        Activity::log([
            'user_id' => $user->id,
            'type' => 'registration',
            'action' => 'created',
            'title' => 'New User Registration',
            'description' => 'New user registered: ' . $user->email,
        ]);

        try {
            $user->notify(new EmailVerificationNotification($verificationCode));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Email sending failed. Verification code for ' . $user->email . ': ' . $verificationCode);
        }

        Auth::login($user);

        return redirect('/student/verify-email');
    }

    public function showVerifyEmail()
    {
        return view('auth.verify-email');
    }

    public function verifyEmail(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'Verification code is required.',
            'code.size' => 'Verification code must be exactly 6 characters.',
        ]);

        $user = $request->user();

        if (!$user) {
            return back()->withErrors(['code' => 'Please login first.']);
        }

        if ($user->verification_code !== strtoupper($request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        if ($user->verification_expires_at && now()->greaterThan($user->verification_expires_at)) {
            return back()->withErrors(['code' => 'Verification code has expired.']);
        }

        $user->email_verified_at = now();
        $user->verification_code = null;
        $user->verification_expires_at = null;
        $user->save();

        Activity::log([
            'user_id' => $user->id,
            'type' => 'verification',
            'action' => 'verified',
            'title' => 'Email Verified',
            'description' => 'User verified their email address',
        ]);

        return redirect('/student/registration/step/1')->with('success', 'Email verified successfully!');
    }

    public function resendVerificationCode(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->email_verified_at) {
            return redirect('/student/registration/step/1');
        }

        $verificationCode = strtoupper(Str::random(6));
        $user->verification_code = $verificationCode;
        $user->verification_expires_at = now()->addMinutes(30);
        $user->save();

        try {
            $user->notify(new EmailVerificationNotification($verificationCode));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::info('Email resend failed. Verification code for ' . $user->email . ': ' . $verificationCode);
        }

        return back()->with('success', 'Verification code has been resent to your email.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
