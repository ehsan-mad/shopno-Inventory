<?php
namespace App\Http\Controllers;

use App\Helper\JwtToken;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use App\Models\Customer;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function userProfile()
    {
        $user = auth()->user();
        return view('user.profile', compact('user'));
    }

    public function registration(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email|max:50',
            'mobile'     => 'nullable|string|max:20',
            'role'       => 'nullable|in:admin,shopKeeper',
            'password'   => 'required|string|min:3',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'mobile'     => $request->mobile,
            'role'       => $request->role,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please login.');
    }

    public function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email'    => 'required|email|max:50',
            'password' => 'required|string|min:3',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return redirect()->back()
                ->with('error', 'Invalid credentials')
                ->withInput();
        }

        $token = JwtToken::generateToken($user->id, $request->email);

        if (!$token) {
            return redirect()->back()
                ->with('error', 'Token generation failed')
                ->withInput();
        }

        return redirect()->route('dashboard')
            ->cookie('token', $token, 60 * 60); // 1 hour
    }

    public function logout()
    {
        return redirect()->route('login')
            ->cookie('token', null, -1);
    }

    public function sendOtp(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found')
                ->withInput();
        }

        $otp = rand(1000, 9999);
        $user->otp = $otp;
        $user->save();

        Mail::to($user->email)->send(new OtpMail($otp));

        return redirect()->back()
            ->with('success', 'Password reset OTP sent to your email');
    }

    public function verifyOtp(Request $request)
    {
        $email = $request->headers->get('email');
        if (!$email) {
            return redirect()->back()
                ->with('error', 'Email header is required');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found');
        }

        if ($user->otp != $request->otp) {
            return redirect()->back()
                ->with('error', 'Invalid OTP');
        }

        $token = JwtToken::resetPasswordToken($user->email);

        if (!$token) {
            return redirect()->back()
                ->with('error', 'Token generation failed');
        }

        return redirect()->route('reset-password')
            ->cookie('token', $token, 60 * 60); // 1 hour
    }

    public function resetPassword(Request $request)
    {
        $token = $request->cookie('token');
        $decoded = JwtToken::verifyToken($token);

        if (!$decoded || !isset($decoded->email)) {
            return redirect()->back()
                ->with('error', 'Invalid or expired token');
        }

        $email = $decoded->email;
        $validate = Validator::make($request->all(), [
            'password' => 'required|string|min:3',
        ]);

        if ($validate->fails()) {
            return redirect()->back()
                ->withErrors($validate);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'User not found');
        }

        $user->password = Hash::make($request->password);
        $user->otp = 0;
        $user->save();

        return redirect()->route('login')
            ->with('success', 'Password reset successfully')
            ->cookie('token', null, -1);
    }
}
