<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class CustomerController extends Controller
{
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        $customer = Customer::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $token = $customer->createToken('api-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        // code equivalient to attempt() method
        $customer = Customer::where('email', $credentials['email'])->first();

        if (! $customer || ! Hash::check($credentials['password'], $customer->password)) {

            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        //------------  ----------------------

        $token = $customer->createToken('api-token')->plainTextToken;

        return response()->json([
            'customer' => $customer,
            'token' => $token,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = Customer::where('email', $request->email)->first();

        if (!$user) {

            return response()->json([
                'message' => 'If this email exists, a reset link was sent.'
            ]);
        }

        $reset_token = Str::random(64);

        DB::table('password_reset_tokens')->updateOrInsert(

            ['email' => $user->email],

            [
                'email' => $user->email,
                'reset_token' => Hash::make($reset_token),
                'created_at' => now(),
            ]
        );

        $resetUrl = env('FRONTEND_URL')
            . '/reset-password?token='
            . $reset_token
            . '&email='
            . urlencode($user->email);

        Mail::raw(

            "Click here to reset your password: {$resetUrl}",

            function ($message) use ($user, $reset_token) {

                $message->to($user->email)
                    ->subject("Reset Password token: $reset_token");
            }
        );

        return response()->json([
            // 'message' => 'If this email exists, a reset link was sent.',
            'email' => $user->email,
            'token' => $reset_token,
        ]);
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'reset_token' => ['required'],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (!$reset) {

            return response()->json([
                'message' => 'Invalid reset request.'
            ], 400);
        }

        $tokenValid = Hash::check(
            $request->reset_token,
            $reset->reset_token
        );

        if (!$tokenValid) {

            return response()->json([
                'message' => 'Invalid token.'
            ], 400);
        }

        if (now()->diffInMinutes($reset->created_at) > 60) {

            return response()->json([
                'message' => 'Token expired.'
            ], 400);
        }

        $user = Customer::where('email', $request->email)->first();

        if (!$user) {

            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        $user->tokens()->delete();

        return response()->json([
            'message' => 'Password reset successful.'
        ]);
    }
}

