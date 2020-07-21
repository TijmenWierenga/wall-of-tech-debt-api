<?php

declare(strict_types=1);

namespace App\Controller\Issues;

use App\Domain\Issues\TagService;
use App\Domain\Issues\TagTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TagController
{
    private TagService $tagService;
    private Manager $transformer;

    public function __construct(TagService $tagService, Manager $transformer)
    {
        $this->tagService = $tagService;
        $this->transformer = $transformer;
    }

    /**
     * @Route("/tags", methods={"GET"})
     */
    public function index(): Response
    {
        $tags = $this->tagService->getAll();

        $data = $this->transformer->createData(new Collection($tags, new TagTransformer()));

        return new JsonResponse($data->toArray());
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
