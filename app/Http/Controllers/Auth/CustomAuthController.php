<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class CustomAuthController extends Controller
{
    // login
    public function loginPost(Request $request) {
        $email = $request->get('email');
        $password = $request->get('password');
        // attempt login for members
        if (Auth::attempt ( array (
            'email' => $email,
            'password' => $password
        ) )) {
            session ( [
                'email' => $email
            ] );
            return response()->json(['status' => 'success', 'message' => __('global.success.login')]);
        } else {
            return response()->json(['status' => 'error', 'message' => __('global.errors.invalid_login')]);
        }
    }
    public function loginGet(Request $request) {
        return view('auth.login');
    }

    // register
    public function registerGet(Request $request) {
        return view('auth.register');
    }
    public function registerPost(Request $request) {
        $name = $request->get('name');
        $email = $request->get('email');
        $password = $request->get('password');
        if (User::where('email', '=', $email)->count() > 0) {
            return response()->json(['status' => 'error', 'message' => __('global.errors.email_exist')]);
        }

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->role = 7;
        $user->state = 1;
        $user->avatar = '/uploads/avatar/avatar-default-icon.png';
        $user->save();

        return response()->json(['status' => 'success', 'message' => __('global.success.register')]);
    }
    public function resetPassword(Request $request) {

    }
}
