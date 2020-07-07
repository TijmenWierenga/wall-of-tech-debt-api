<?php

declare(strict_types=1);

namespace App\Domain\Shared;

use League\Fractal\Pagination\CursorInterface;
use League\Fractal\Pagination\PaginatorInterface;
use League\Fractal\Resource\ResourceInterface;
use League\Fractal\Serializer\SerializerAbstract;

final class SimpleSerializer extends SerializerAbstract
{

    public function collection($resourceKey, array $data): array
    {
        return $data;
    }

    public function item($resourceKey, array $data): array
    {
        return $data;
    }

    public function null(): array
    {
        return [];
    }

    public function includedData(ResourceInterface $resource, array $data): array
    {
        return $data;
    }

    public function meta(array $meta): array
    {
        return $meta;
    }

    public function paginator(PaginatorInterface $paginator): array
    {
        return [];
    }

    public function cursor(CursorInterface $cursor): array
    {
        return [];
    }
}
