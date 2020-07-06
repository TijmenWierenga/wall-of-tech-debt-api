<?php

declare(strict_types=1);

namespace App\Controller\Issues;

use App\Domain\Issues\Issue;
use App\Domain\Issues\IssueService;
use App\Domain\Issues\IssueTransformer;
use App\Domain\Issues\Tag;
use App\Domain\Issues\Vote;
use App\Domain\Security\User;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

final class IssueController
{
    private IssueService $issueService;
    private Security $security;
    private Manager $transformer;

    public function __construct(IssueService $issueService, Security $security, Manager $transformer)
    {
        $this->issueService = $issueService;
        $this->security = $security;
        $this->transformer = $transformer;
    }

    /**
     * @Route("/issues", methods={"GET"})
     */
    public function index(): Response
    {
        $issues = $this->issueService->getAll();

        $data = $this->transformer->createData(new Collection($issues, new IssueTransformer()));

        return new JsonResponse($data->toArray());
    }

    /**
     * @Route("/issues", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $this->security->getUser();
        $userId = $user->getId();

        $data = json_decode((string)$request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $issue = $this->issueService->create(
            $data['title'],
            $userId
        );

        $data = $this->transformer->createData(new Item($issue, new IssueTransformer()));

        return new JsonResponse($data->toArray(), Response::HTTP_CREATED, [
            'Content-Type' => 'application/json'
        ]);
    }
}
