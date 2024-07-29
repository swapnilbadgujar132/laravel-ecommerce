<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
        
        //  $request->session()->flash('success', 'Operation was successful!');


        if ($user) {
            return redirect()->route('user.dashboard')->with('success', 'Login successfully');
        } else {
            return redirect()->route('user.register')->with('error', 'Invalid email and password');
        }
    }

    public function ForgetPassword(Request $request)
     {
        $request->validate(['email' => 'required|email']);
        
        $token = Str::random(60);
        $email = $request->email;
        
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['token' => $token,
             'created_at' => now()]
        );

        Mail::to($email)->send(new ResetPasswordMail($email, $token));
        session()->flash('success', 'Password reset link has been sent to your email.');
        return back();
     }
 
    public function ResetPassword(Request $request){
        $data=$request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        
      $status = DB::table('password_reset_tokens')->whereRAW('email=? AND token=?',[$request->email,$request->token])->exists();
        if ($status) {
         $update = User::whereRAW('email = ?',[$request->email])->update([
            'password'=>Hash::make($request->password),
            'remember_token'=>Str::random(60),
          ]);
          session()->flash('successAlert', 'Password reset Succsefully.');
          return redirect()->route('user.register');
        }
        else {
            return back()->with(['error' => 'Unsuccesfully']);
    }
}}










