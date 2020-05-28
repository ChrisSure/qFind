<?php

namespace App\Service\Auth;

use Illuminate\Support\Facades\Cookie;

class UserGlobalService
{
    private static $minutes = 6 * 24 * 30;

    private static $siteName;

    public static function setUserData(string $token, object $data, $remember): void
    {
        self::$siteName = env('APP_NAME', null);
        ($remember) ? self::saveToCoockie($token, $data) : self::saveToSession($token, $data);
    }

    public static function saveToCoockie($token, $data): void
    {
        Cookie::queue(self::$siteName . '_jwt_token', $token, self::$minutes);
        Cookie::queue(self::$siteName . '_user_id', $data->id, self::$minutes);
        Cookie::queue(self::$siteName . '_user_email', $data->email, self::$minutes);
        Cookie::queue(self::$siteName . '_user_role', $data->roles[0], self::$minutes);
        Cookie::queue(self::$siteName . '_user_iat', $data->iat, self::$minutes);
        Cookie::queue(self::$siteName . '_user_exp', $data->exp, self::$minutes);
    }

    public static function saveToSession($token, $data): void
    {
        session([self::$siteName . '_jwt_token' => $token]);
        session([self::$siteName . '_user_id' => $data->id]);
        session([self::$siteName . '_user_email' => $data->email]);
        session([self::$siteName . '_user_role' => $data->roles[0]]);
        session([self::$siteName . '_user_iat' => $data->iat]);
        session([self::$siteName . '_user_exp' => $data->exp]);
    }

    public static function removeData(): void
    {
        $siteName = env('APP_NAME', null);
        session()->forget($siteName . '_jwt_token');
        session()->forget($siteName . '_user_id');
        session()->forget($siteName . '_user_email');
        session()->forget($siteName . '_user_role');
        session()->forget($siteName . '_user_iat');
        session()->forget($siteName . '_user_exp');

        Cookie::unqueue($siteName . '_jwt_token');
        Cookie::unqueue($siteName . '_user_id');
        Cookie::unqueue($siteName . '_user_email');
        Cookie::unqueue($siteName . '_user_role');
        Cookie::unqueue($siteName . '_user_iat');
        Cookie::unqueue($siteName . '_user_exp');
    }

    public static function getId(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_id') !== null)
            ? session($siteName . '_user_id')
            : Cookie::get($siteName . '_user_id');
    }

    public static function getEmail(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_email') !== null)
            ? session($siteName . '_user_email')
            : Cookie::get($siteName . '_user_email');
    }

    public static function getRole(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_role') !== null)
            ? session($siteName . '_user_role')
            : Cookie::get($siteName . '_user_role');
    }

    public static function getIat(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_iat') !== null)
            ? session($siteName . '_user_iat')
            : Cookie::get($siteName . '_user_iat');
    }

    public static function getExp(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_exp') !== null)
            ? session($siteName . '_user_exp')
            : Cookie::get($siteName . '_user_exp');
    }
}
