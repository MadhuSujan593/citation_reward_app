<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegisterForm()
    {
        return view('auth.register');
    }
    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name'  => 'required|string|max:255',
                'email'      => 'required|email|unique:users,email',
                'password'   => 'required|string|min:6',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $request->email,
                'password'   => Hash::make($request->password),
            ]);
            Log::info('User registered: ' . $request->email);
            return redirect()->route('login')->with('success', 'Registration successful. Please login to continue!!.');
        } catch (\Exception $e) {
            Log::info('User registered Failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Something went wrong. Please try again.')
                ->withInput();
        }
    }

    // Show Login Form
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Handle Login
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();

                return redirect()->intended(route('dashboard'));
            }

            // If authentication fails, throw validation exception
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        } catch (ValidationException $e) {
            // Validation exception, rethrow to show errors on form
            throw $e;
        } catch (\Exception $e) {
            // Log unexpected errors
            Log::error('Login error: ' . $e->getMessage());
            // Redirect back with generic error message
            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again later.'])->withInput();
        }
    }


    // Handle Logout
    public function logout(Request $request)
    {
        try {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login');
        } catch (\Exception $e) {
            Log::error('Logout error: ' . $e->getMessage());

            return redirect()->route('dashboard')->withErrors(['error' => 'Failed to logout. Please try again.']);
        }
    }


    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // Handle sending reset link email
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->input('email');

        if (!User::where('email', $email)->exists()) {
            return back()->withErrors(['email' => 'The provided email address is not registered in our system.']);
        }

        try {
            $status = Password::sendResetLink(['email' => $email]);
            Log::error('Password reset link sent successfully: ' . $status);
            if ($status === Password::RESET_LINK_SENT) {
                return back()->with('status', 'A password reset link has been sent to your email.');
            } else {
                return back()->withErrors(['email' => 'Failed to send reset link. Please try again later.']);
            }
        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Failed to send reset link. Please try again later.']);
        }
    }


    // Show Reset Password Form
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    // Handle Reset Password
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|string|confirmed|min:6',
        ]);

        try {
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    $user->forceFill([
                        'password' => Hash::make($password),
                        'remember_token' => Str::random(60),
                    ])->save();
                }
            );

            if ($status === Password::PASSWORD_RESET) {
                return back()->with('status', 'Password reset successful! You can now log in with your new password.');
            }

            return back()->withErrors(['email' => __($status)]);
        } catch (\Exception $e) {
            // Log the exception if you want
            Log::error('Password reset error: ' . $e->getMessage());

            return back()->withErrors(['error' => 'An unexpected error occurred. Please try again later.']);
        }
    }

    public function deleteUserAccount(Request $request)
    {
        try {
            $user = $request->user();

            Auth::logout();

            // Attempt to delete the user
            $user->delete();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return response()->json([
                'status' => 'success',
                'message' => 'Your profile has been deleted successfully.'
            ], 200);

        } catch (Exception $e) {
            // Log the exception for debugging
            Log::error('Profile deletion failed: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete your profile. Please try again later.'
            ], 500);
        }
    }
}
