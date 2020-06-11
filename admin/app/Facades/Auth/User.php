<?php

namespace App\Facades\Auth;

use Illuminate\Support\Facades\Facade;

/**
 * Class User
 * @package App\Facades\Auth
 *
 * @method static void setUserData(string $token, object $data)
 * @method static void removeData()
 * @method static bool isAuth()
 * @method static string getToken()
 * @method static int getId()
 * @method static string getEmail()
 * @method static string getRole()
 * @method static int getIat()
 * @method static int getExp()
 */

class User extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'user';
    }
}
