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
    protected $smsService;

    public function __construct(\App\Services\SmsService $smsService)
    {
        $this->smsService = $smsService;
    }
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
            'phone' => 'required|string|min:10|max:20|regex:/^[0-9]+$/',
            'password' => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::defaults()->min(8)->letters()->numbers()->symbols()],
            'terms' => 'required|accepted',
        ], [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already registered.',
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number must be at least 10 digits.',
            'phone.max' => 'Phone number cannot exceed 20 digits.',
            'phone.regex' => 'Please enter a valid phone number (numbers only).',
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Passwords do not match.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.letters' => 'Password must contain at least one letter.',
            'password.numbers' => 'Password must contain at least one number.',
            'password.symbols' => 'Password must contain at least one special character.',
            'terms.required' => 'You must agree to the Terms & Conditions.',
            'terms.accepted' => 'You must agree to the Terms & Conditions.',
        ]);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'student',
        ]);

        $user->profile()->create(['phone' => $request->phone]);

        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
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

        // Generate and send phone verification code
        $phoneCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $phoneCode;
        $user->verification_expires_at = now()->addMinutes(30);
        $user->save();

        $this->smsService->send($user->profile->phone, "Your Orange Academy verification code is: {$phoneCode}");

        return redirect('/student/verify-phone')->with('success', 'Email verified successfully! A verification code has been sent to your phone.');
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

        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
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

    public function showVerifyPhone()
    {
        return view('auth.verify-phone');
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ], [
            'code.required' => 'Verification code is required.',
            'code.size' => 'Verification code must be exactly 6 characters.',
        ]);

        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->verification_code !== strtoupper($request->code)) {
            return back()->withErrors(['code' => 'Invalid verification code.']);
        }

        if ($user->verification_expires_at && now()->greaterThan($user->verification_expires_at)) {
            return back()->withErrors(['code' => 'Verification code has expired.']);
        }

        if ($user->profile) {
            $user->profile->update([
                'phone_verified' => true,
                'phone_verified_at' => now(),
            ]);
        }
        $user->verification_code = null;
        $user->verification_expires_at = null;
        $user->save();

        Activity::log([
            'user_id' => $user->id,
            'type' => 'verification',
            'action' => 'verified',
            'title' => 'Phone Verified',
            'description' => 'User verified their phone number',
        ]);

        return redirect('/student/registration/step/1')->with('success', 'Phone verified successfully!');
    }

    public function resendPhoneCode(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return redirect('/login');
        }

        if ($user->profile && $user->profile->phone_verified) {
            return redirect('/student/registration/step/1');
        }

        $verificationCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->verification_code = $verificationCode;
        $user->verification_expires_at = now()->addMinutes(30);
        $user->save();

        $this->smsService->send($user->profile->phone, "Your Orange Academy verification code is: {$verificationCode}");

        return back()->with('success', 'Verification code has been resent to your phone.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
