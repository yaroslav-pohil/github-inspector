<?php

declare(strict_types=1);

namespace App\Services\Github;

use App\Services\Github\Responses\PullRequest;
use App\Services\Github\Responses\ResponseFactory;
use Github\AuthMethod;
use Github\Client;
use Illuminate\Support\Collection;

class Github
{
    /**
     * @var bool
     */
    protected $authenticated = false;
    /**
     * @var string
     */
    protected $authToken;
    /**
     * @var Client
     */
    protected $client;
    /**
     * @var array
     */
    protected $repositories;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->authToken = config('services.github.auth_token');
        $this->repositories = config('services.github.repositories', []);
    }

    public function getAllPullRequestsReviewRequests(): array
    {
        $result = [];
        $pullRequests = $this->getOpenPullRequests();

        return $result;
    }

    protected function callClient(): Client
    {
        if (!$this->authenticated) {
            $this->client->authenticate($this->authToken, null, AuthMethod::ACCESS_TOKEN);
            $this->authenticated = true;
        }

        return $this->client;
    }

    public function getOpenPullRequests(): Collection
    {
        $result = new Collection();
        foreach ($this->repositories as $repository) {
            list($ownerUsername, $repositoryName) = explode('/', $repository);
            $page = 1;

            do {
                $apiResponse = $this->callClient()->pullRequests()->all($ownerUsername, $repositoryName, [
                    'state' => PullRequest::STATE_OPEN,
                    'per_page' => 50,
                    'page' => $page++,
                ]);

                $result = $result->merge(ResponseFactory::buildMany(PullRequest::class, $apiResponse));
            } while (count($apiResponse) > 0);

        }

        return $result;
    }
}
