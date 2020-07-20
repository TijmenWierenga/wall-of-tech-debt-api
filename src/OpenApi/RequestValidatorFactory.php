<?php

declare(strict_types=1);

namespace App\OpenApi;

use League\OpenAPIValidation\PSR7\ServerRequestValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use Psr\Cache\CacheItemPoolInterface;

final class RequestValidatorFactory
{
    public static function createValidator(
        CacheItemPoolInterface $cache
    ): ServerRequestValidator {
        return (new ValidatorBuilder())
            ->fromYamlFile(__DIR__ . '/../../openapi.v1.yaml')
            ->setCache($cache)
            ->getServerRequestValidator();
    }
}
