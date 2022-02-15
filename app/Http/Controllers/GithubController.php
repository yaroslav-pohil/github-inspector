<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\Github\Github;
use App\Services\Github\Responses\PullRequest;
use Illuminate\Support\Arr;
use Illuminate\View\View;

class GithubController extends Controller
{
    /**
     * @var Github
     */
    protected $github;

    public function __construct(Github $github)
    {
        $this->github = $github;
    }

    public function getUserStatistics(): View
    {
        $pullRequests = $this->github->getOpenPullRequests();

        $openedPrs = []; // List of non-reviewed PRs. Key is username, value is amount
        $codeReviewDebts = []; // assoc array of PRs that needs to be reviewed, grouped by users

        /** @var PullRequest $pr */
        foreach ($pullRequests as $pr) {
            $prOwner = $pr->user->login;

            // We're interested only in non-reviewed PRs
            if (count($pr->requested_reviewers) > 0) {

                // Add to total opened for user
                if (!isset($openedPrs[$prOwner])) {
                    $openedPrs[$prOwner] = [];
                }
                $openedPrs[$prOwner][] = $pr->html_url;

                // Add debts
                foreach ($pr->requested_reviewers as $debt) {
                    $debtOwner = $debt->login;
                    if (!isset($codeReviewDebts[$debtOwner])) {
                        $codeReviewDebts[$debtOwner] = [];
                    }

                    $codeReviewDebts[$debtOwner][] = $pr->html_url;
                }
            }

        }

        $users = Arr::sort(
            array_unique(array_merge(array_keys($openedPrs), array_keys($codeReviewDebts))),
            function ($userLogin) use ($openedPrs, $codeReviewDebts) {
                return count($openedPrs[$userLogin] ?? []) + count($codeReviewDebts[$userLogin] ?? []);
            }
        );

        return view('user_statistics')
            ->with('openedPrs', $openedPrs)
            ->with('codeReviewDebts', $codeReviewDebts)
            ->with('users', $users);
    }
}
