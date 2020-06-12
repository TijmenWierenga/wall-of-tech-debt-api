<?php

declare(strict_types=1);

namespace App\Controller\Issues;

use App\Domain\Issues\Issue;
use App\Domain\Issues\IssueService;
use App\Domain\Issues\Tag;
use App\Domain\Issues\Vote;
use App\Domain\Security\User;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class IssueController
{
    /** @var IssueService */
    private IssueService $issueService;
    private Security $security;

    public function __construct(IssueService $issueService, Security $security)
    {
        $this->issueService = $issueService;
        $this->security = $security;
    }

    /**
     * @Route("/issues", methods={"GET"})
     */
    public function index(): Response
    {
        $issues = $this->issueService->getAll()->map(fn (Issue $issue): array => [
            'id' => $issue->getId()->toString(),
            'title' => $issue->getTitle(),
            'createdAt' => $issue->getCreatedAt()->format(DATE_ATOM),
            'author' => $issue->getAuthorId()->toString(),
            'votes' => $issue->getVotes()->map(fn (Vote $vote): array => [
                'by' => $vote->getUserId()->toString(),
                'amount' => $vote->getAmount()
            ])->toArray(),
            'tags' => $issue->getTags()->map(fn (Tag $tag): array => [
                'id' => $tag->getId(),
                'name' => $tag->name
            ])->toArray()
        ])->toArray();

        return new JsonResponse($issues);
    }

    /**
     * @Route("/issues", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $userId = $user->getId();

        $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->issueService->create(
            $data['title'],
            $userId
        );

        return new Response(null, Response::HTTP_CREATED, [
            'Content-Type' => 'application/json'
        ]);
    }
}
