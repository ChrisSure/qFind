<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Service\Auth\AuthService;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $data = $request->only(['email', 'password', 'remember']);

        $response = Http::asForm()->post($this->apiHost . '/auth/signin', [
            'email' => $data['email'],
            'password' => $data['password']
        ]);

        if (!empty($response['error'])) {
            return redirect()->route('login')->with('error', $response['error']);
        } else {
            $this->authService->setToken($response['token'], isset($data['remember']) ?? $data['remember']);
            return redirect()->route('admin.home');
        }
    }
}
