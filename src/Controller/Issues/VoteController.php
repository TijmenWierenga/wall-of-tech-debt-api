<?php

declare(strict_types=1);

namespace App\Controller\Issues;

use App\Domain\Issues\VoteService;
use App\Domain\Security\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class VoteController
{
    /** @var VoteService */
    private VoteService $voteService;
    private Security $security;

    public function __construct(VoteService $voteService, Security $security)
    {
        $this->voteService = $voteService;
        $this->security = $security;
    }

    /**
     * @Route("/issues/{uuid}/upvote", methods={"PUT"})
     */
    public function addVote(Request $request, string $uuid): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $userId = $user->getId();

        $amount = json_decode(
            (string)$request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['amount'];

        $this->voteService->addVote(
            Uuid::fromString($uuid),
            $userId,
            $amount
        );

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * @Route("/issues/{uuid}/downvote", methods={"PUT"})
     */
    public function removeVote(Request $request, string $uuid): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $userId = $user->getId();

        $amount = json_decode(
            (string)$request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        )['amount'];

        $this->voteService->removeVote(
            Uuid::fromString($uuid),
            $userId,
            $amount
        );

        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
