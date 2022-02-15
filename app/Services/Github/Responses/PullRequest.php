<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

class PullRequest extends AbstractResponse
{
    public const STATE_OPEN = 'open';

    protected $fillable = [
        'url',
        'id',
        'number',
        'state',
        'title',
        'user',
        'requested_reviewers',
    ];

    protected $casts = [
        'id' => 'int',
        'number' => 'int',
        'user' => User::class,
        'requested_reviewers.*' => RequestedReviewer::class,
    ];
}
