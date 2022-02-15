<?php

declare(strict_types=1);

return [
    'github' => [
        /**
         * Can be generated at https://github.com/settings/tokens
         */
        'auth_token' => env('GITHUB_AUTH_TOKEN'),

        /**
         * Comma-separated list of repositories, e.g. "laravel/laravel,spatie/laravel-permission"
         */
        'repositories' => explode(',', env('GITHUB_REPOSITORIES')),
    ],
];
