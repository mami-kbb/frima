<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.profile');
    }

    public function edit()
    {
        return view('auth.profile_edit');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'login' => 'ログイン情報が登録されていません',
            ])->withInput();
        }

        return redirect()->rote('dashboard');
    }
}
