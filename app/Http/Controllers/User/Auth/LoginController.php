<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;


class LoginController extends Controller
{
    function index(): View
    {
        return view('user.auth.login');
    }

    function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email_login' => 'required|email|exists:users,email',
            'password_login' => [
                'required',
                'min:8',
            ],
        ], [
            'email_login.email' => 'Please enter a valid email address.',
            'email_login.exists' => 'The email address is not registered.',
            'password_login.required' => 'The password field is required.',
            'password_login.min' => 'The password must be at least 8 characters long.',
        ]);
        $user = Auth::attempt(['email' => $request->email_login, 'password' => $request->password_login]);
        if ($user) {
            return redirect()->route('user.dashboard')->with('success', 'Login successfully');
        } else {
            return redirect()->route('user.register')->with('error', 'Invalid email and password');
        }
    }

    public function ForgetPassword(Request $request){
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );
    
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        } else {
            return back()->withErrors(['email' => __($status)]);
        }
    }
          
    public function ResetPassword(Request $request){
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60), // Use remember_token for password reset token
                ])->save();
                
    
                event(new PasswordReset($user));
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('status', __($status));
        } else {
            return back()->withErrors(['email' => [__($status)]]);
        }
       
    }
    
}
