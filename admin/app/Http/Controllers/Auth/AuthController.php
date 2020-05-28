<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Service\Auth\AuthService;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    private $authService;

    public function __construct(AuthService $authService)
    {
        parent::__construct();
        $this->authService = $authService;
    }

    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $data = $request->only(['email', 'password', 'remember']);

        $response = Http::asForm()->post($this->apiHost . '/auth/signin', [
            'email' => $data['email'],
            'password' => $data['password'],
            'type' => 'admin'
        ]);

        if (!empty($response['error'])) {
            return redirect()->route('login')->with('error', $response['error']);
        } else {
            $this->authService->setToken($response['token'], isset($data['remember']) ?? $data['remember']);
            return redirect()->route('admin.home');
        }
    }

    public function logout()
    {
        $this->authService->logout();
    }
}
