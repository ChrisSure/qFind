<?php

namespace App\Service\User;

class UserService
{
    public static $ROLE_USER = "ROLE_USER";
    public static $ROLE_ADMIN = "ROLE_ADMIN";
    public static $ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    public static $STATUS_NEW = "new";
    public static $STATUS_ACTIVE = "active";
    public static $STATUS_BLOCKED = "blocked";

    public static function statusList(): array
    {
        return [
            self::$STATUS_NEW => 'new',
            self::$STATUS_ACTIVE => 'active',
            self::$STATUS_BLOCKED => 'blocked',
        ];
    }

    public static function rolesList(): array
    {
        return [
            self::$ROLE_USER => 'ROLE_USER',
            self::$ROLE_ADMIN => 'ROLE_ADMIN',
            self::$ROLE_SUPER_ADMIN => 'ROLE_SUPER_ADMIN',
        ];
    }

}
