<?php

declare(strict_types=1);

namespace App\Controller\Issues;

use App\Domain\Issues\TagService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TagController
{
    private TagService $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

    /**
     * @Route("/tags", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = json_decode((string) $request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $this->tagService->create($data['name']);

        return new Response(null, Response::HTTP_CREATED);
    }
}
