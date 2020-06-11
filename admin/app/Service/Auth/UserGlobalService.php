<?php

namespace App\Service\Auth;

class UserGlobalService
{
    private static $minutes = 6 * 24 * 30;

    private static $siteName;

    public static function setUserData(string $token, object $data): void
    {
        self::$siteName = env('APP_NAME', null);
        self::saveToSession($token, $data);
    }

    public static function saveToSession($token, $data): void
    {
        session([self::$siteName . '_jwt_token' => $token]);
        session([self::$siteName . '_user_id' => $data->id]);
        session([self::$siteName . '_user_email' => $data->email]);
        session([self::$siteName . '_user_role' => $data->roles]);
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
    }

    public static function isAuth(): bool
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_jwt_token') !== null) ? true : false;
    }

    public static function getToken(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_jwt_token') !== null) ? session($siteName . '_jwt_token') : null;
    }

    public static function getId(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_id') !== null) ? session($siteName . '_user_id') : null;
    }

    public static function getEmail(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_email') !== null) ? session($siteName . '_user_email') : null;
    }

    public static function getRole(): ?string
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_role') !== null) ? session($siteName . '_user_role') : null;
    }

    public static function getIat(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_iat') !== null) ? session($siteName . '_user_iat') : null;
    }

    public static function getExp(): ?int
    {
        $siteName = env('APP_NAME', null);
        return (session($siteName . '_user_exp') !== null) ? session($siteName . '_user_exp') : null;
    }
}
