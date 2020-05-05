<?php
declare(strict_types=1);

namespace App\Controller;

use App\Issues\VoteService;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VoteController
{

    /** @var VoteService */
    private VoteService $voteService;

    public function __construct(VoteService $voteService)
    {
        $this->voteService = $voteService;
    }

    /**
     * @Route("/issues/{uuid}/upvote", methods={"POST"})
     */
    public function addVote(Request $request, string $uuid): Response
    {
        $amount = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)['amount'];

        $this->voteService->addVote(
            Uuid::fromString($uuid),
            Uuid::fromString(IssueController::FAKE_USER_ID),
            $amount
        );

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/issues/{uuid}/downvote", methods={"POST"})
     */
    public function removeVote(Request $request, string $uuid): Response
    {
        $amount = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR)['amount'];

        $this->voteService->removeVote(
            Uuid::fromString($uuid),
            Uuid::fromString(IssueController::FAKE_USER_ID),
            $amount
        );

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
