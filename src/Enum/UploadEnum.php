<?php declare(strict_types=1);

namespace App\Enum;

class UploadEnum
{
    public const string PNG = 'png';
    public const string JPEG = 'jpeg';
    public const string CSV = 'csv';
    public const string JPG = 'jpg';

    public const array ALL = [
        self::PNG,
        self::JPEG,
        self::JPG,
        self::CSV,
    ];
}
