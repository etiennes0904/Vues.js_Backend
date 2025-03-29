<?php declare(strict_types=1);

namespace App\Enum;

class RoleEnum
{
    public const string ROLE_USER = 'ROLE_USER';
    public const string ROLE_ADMIN = 'ROLE_ADMIN';

    public const array ALL = [
        self::ROLE_USER,
        self::ROLE_ADMIN,
    ];
}
