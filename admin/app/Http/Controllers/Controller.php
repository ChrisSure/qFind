<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Facades\Auth\User;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $apiHost;

    public function __construct()
    {
        $this->apiHost = env('API_HOST', 'http://172.20.0.9');
    }

    protected function getToken(): ?string
    {
        return User::getToken();
    }

}
