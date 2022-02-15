<?php

declare(strict_types=1);

namespace App\Services\Github\Responses;

/**
 * @property-read int $id
 * @property-read string $html_url
 * @property-read int $number
 * @property-read string $state
 * @property-read string $title
 * @property-read User $user
 * @property-read RequestedReviewer[] $requested_reviewers
 */
class PullRequest extends AbstractResponse
{
    protected $fillable = [
        'id',
        'html_url',
        'number',
        'state',
        'title',
        'user',
        'requested_reviewers',
    ];

    public const STATE_OPEN = 'open';

    protected $casts = [
        'id' => 'int',
        'number' => 'int',
        'user' => User::class,
        'requested_reviewers.*' => RequestedReviewer::class,
    ];
}
