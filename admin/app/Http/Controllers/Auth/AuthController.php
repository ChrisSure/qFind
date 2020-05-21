<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only(['email', 'password', 'remember']);

        $response = Http::withOptions([
            'port' => 9999,
        ])->post('http://localhost/auth/signin', [
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        print_r($response);exit();
    }
}
