<?php

declare(strict_types=1);

namespace App\OpenApi;

use cebe\openapi\Reader;
use cebe\openapi\ReferenceContext;
use cebe\openapi\spec\OpenApi;
use InvalidArgumentException;
use League\OpenAPIValidation\PSR7\CacheableSchemaFactory;

final class UrlSchemaFactory implements CacheableSchemaFactory
{
    private string $schemaUrl;

    public function __construct(string $schemaUrl)
    {
        if (! filter_var($schemaUrl, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException(sprintf('%s is not a valid Url', $schemaUrl));
        }

        $this->schemaUrl = $schemaUrl;
    }

    public function getCacheKey(): string
    {
        return 'openapi_' . crc32($this->schemaUrl);
    }

    public function createSchema(): OpenApi
    {
        $contents = file_get_contents($this->schemaUrl);

        /** @var OpenApi $schema */
        $schema = Reader::readFromYaml($contents);

        $schema->resolveReferences(new ReferenceContext($schema, '/'));

        return $schema;
    }
}
