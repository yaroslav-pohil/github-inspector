<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

/**
 * @property-read int $id
 * @property-read string $login
 * @property-read string $url
 */
class User extends AbstractResponse
{
    protected $fillable = [
        'id',
        'login',
        'url',
    ];
}
