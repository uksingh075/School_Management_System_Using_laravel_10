<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login()
    {
        if (!empty(Auth::check())) {
            if (Auth::user()->user_type == 1) {
                return redirect('admin/dashboard');
            }

            if (Auth::user()->user_type == 2) {
                return redirect('teacher/dashboard');
            }

            if (Auth::user()->user_type == 3) {
                return redirect('student/dashboard');
            }

            if (Auth::user()->user_type == 4) {
                return redirect('parent/dashboard');
            }
        }
        return view('auth.login');
    }

    public function AuthLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->passes()) {

            $remember = !empty($request->remember) ? true : false;
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {

                if (Auth::user()->user_type == 1) {
                    return redirect('admin/dashboard');
                }

                if (Auth::user()->user_type == 2) {
                    return redirect('teacher/dashboard');
                }

                if (Auth::user()->user_type == 3) {
                    return redirect('student/dashboard');
                }

                if (Auth::user()->user_type == 4) {
                    return redirect('parent/dashboard');
                }
            } else {
                return redirect()->back()->with('error', 'Invalid Email or Password');
            }
        } else {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    public function forgotpassword()
    {
        return view('auth.forgot');
    }

    public function PostForgotPassword(Request $request)
    {
        $user = User::getEmailSingle($request->email);
        if (!empty($user)) {
            $user->remember_token = Str::random(30);
            $user->save();

            Mail::to($user->email)->send(new ForgotPasswordMail($user));

            return redirect()->back()->with('success', 'Please check your email and reset your password');
        } else {
            return redirect()->back()->with('error', 'Email does not exist');
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect(url(''));
    }
}
