<?php

declare(strict_types=1);

namespace App\Controller;

use App\Issues\Issue;
use App\Issues\IssueService;
use App\Issues\Vote;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class IssueController
{
    public const FAKE_USER_ID = '12547459-7701-2983-d145-1182d1d67e02';

    /** @var IssueService */
    private IssueService $issueService;

    public function __construct(IssueService $issueService)
    {
        $this->issueService = $issueService;
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
            ])->toArray()
        ])->toArray();

        return new JsonResponse($issues);
    }

    /**
     * @Route("/issues", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        // TODO: Get user from security

        $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->issueService->create(
            $data['title'],
            Uuid::fromString(self::FAKE_USER_ID)
        );

        return new Response(null, Response::HTTP_CREATED, [
            'Content-Type' => 'application/json'
        ]);
    }
}
