<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

/**
 * @property-read int $id
 * @property-read string $login Username of assigned reviewer.
 */
class RequestedReviewer extends AbstractResponse
{
    protected $fillable = [
        'id',
        'login',
    ];
}
