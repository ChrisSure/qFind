<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Facades\Auth\User;

class HomeController  extends Controller
{
    public function index()
    {
        echo User::getId();
        echo User::getEmail();
        echo User::getRole();
        echo User::getIat();
        echo User::getExp();
    }
}
