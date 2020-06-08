<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'Auth\AuthController@showLoginForm')->name('login');
Route::post('/', 'Auth\AuthController@login');

Route::group(
    [
        'prefix' => 'admin',
        'as' => 'admin.',
        'namespace' => 'Admin',
        'middleware' => ['auth'],
    ],
    function () {
        Route::get('/logout', '\App\Http\Controllers\Auth\AuthController@logout')->name('logout');
        Route::get('/', 'HomeController@index')->name('home');

        Route::resource('/users', 'User\UserController');

        Route::get('/users/{id}/activate', 'User\UserController@activate')->name('users.activate');
        Route::get('/users/{id}/block', 'User\UserController@block')->name('users.block');

    });

