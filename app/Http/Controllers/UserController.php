<?php
namespace App\Http\Controllers;

use App\Helper\JwtToken;
use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function registration(Request $request)
    {
        $validate = Validator::make($request->all(), [

            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|unique:users,email|max:50',
            'mobile'     => 'nullable|string|max:20',
            'role'       => 'nullable|in:admin,shopKeeper',
            'password'   => 'required|string|min:3',
            'otp'        => 'nullable|integer|max:4',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validate->errors()->first(),
            ], 400);

        } else {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'password'   => $request->password,
                'mobile'     => $request->mobile,
                'role'       => $request->role,
                'otp'        => $request->otp,

            ]);
            return response()->json([
                'message' => 'User registered successfully',
                'data'    => $user,

            ], 201);
        }
    }
    public function login(Request $request)
    {
        // Login logic here
        $validate = Validator::make($request->all(), [
            'email'    => 'required|email|max:50',
            'password' => 'required|string|min:3',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validate->errors(),
            ], 400);
        }
        $user = User::where('email', $request->email)->where('password', $request->password)->first();
        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found or invalid credentials',
            ], 404);
        } else {
            $token = JwtToken::generateToken($user->id, $request->email);

            if (! $token) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Token generation failed',
                ], 500);
            }
            // setcookie('token', $token, time() + (86400 * 30), '/'); // 30 days

            return response()->json([

                'message' => 'Login successful',
                'token'   => $token,

            ], 200)->cookie('token', $token, 60 * 60)       ; // 1 hour       
        }

    }

    public function logout()
    {
        return response()->json([
            'message' => 'Logout successful',
        ], 200)->cookie('token', null, -1);
    }

    public function sendOtp(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|email|max:50',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validate->errors(),
            ], 400);
        }
        // Password reset logic here
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        } else {

            $otp       = rand(1000, 9999);
            $user->otp = $otp;
            $user->save();

            // Send the reset otp to the user's email

            // You can use Laravel's Mail facade to send the email
            Mail::to($user->email)->send(new OtpMail($otp));
            return response()->json([
                'message' => 'Password reset OTP sent to your email',
                'otp'     => $otp, // For testing purposes, you can remove this in production
            ], 200);
        }

    }

    public function verifyOtp(Request $request)
    {
        $email = $request->headers->get('email');
        if (! $email) {
            return response()->json([
                'status'  => false,
                'message' => 'Email header is required',
            ], 400);
        }
        // $validate = Validator::make($request->header('email'), [
        //     'email' => 'required|email|max:50',
        //     'otp'   => 'required|integer|max:4',
        // ]);
        // if ($validate->fails()) {
        //     return response()->json([
        //         'status'  => false,
        //         'message' => $validate->errors(),
        //     ], 400);
        // }
        $user = User::where('email', $email)->first();
        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        } elseif ($user->otp != $request->otp) {
            return response()->json([
                'status'  => false,
                'message' => 'Invalid OTP',
            ], 400);
        } else {
            // OTP verified successfully
            $token = JwtToken::resetPasswordToken($user->email);
            if (! $token) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Token generation failed',
                ], 500);
            }

            return response()->json([
                'message' => 'OTP verified successfully',
            ], 200)->cookie('token', $token, 60 * 60); // 1 hour
        }
    }

    public function resetPassword(Request $request)
    {$token = $request->cookie('token');
        $decoded   = JwtToken::verifyToken($token);
        if (! $decoded || ! isset($decoded->email)) {
            return response()->json([
                'status'  => false,
                'message' => 'decode failed',
            ], 401);
        }

        $email = $decoded->email;
        if (! $email) {
            return response()->json([
                'status'  => false,
                'message' => 'Email required',
            ], 400);
        }
        $validate = Validator::make($request->all(), [

            'password' => 'required|string|min:3',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'status'  => false,
                'message' => $validate->errors(),
            ], 400);
        }
        $user = User::where('email', $email)->first();
        if (! $user) {
            return response()->json([
                'status'  => false,
                'message' => 'User not found',
            ], 404);
        } else {
            $update = User::where('email', $email)->update([
                'password' => $request->password,
            ]);
            $user->otp = 0; // Reset OTP after successful password reset

            return response()->json([
                'message' => 'Password reset successfully',
                'data'    => $update,
            ], 200)->cookie('token', null, -1); 
        }}

}
