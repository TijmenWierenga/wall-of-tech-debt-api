<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Domain\Security\TokenService;
use App\Domain\Security\User;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\OperationAddress;
use League\OpenAPIValidation\PSR7\ResponseValidator;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\BreadCrumb;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ContractTestCase extends WebTestCase
{
    protected ResponseValidator $validator;
    protected KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = static::createClient();

        $this->validator = (new ValidatorBuilder())
            ->fromYamlFile(__DIR__ . '/../../openapi.v1.yaml')
            ->setCache(self::$container->get(CacheItemPoolInterface::class))
            ->getResponseValidator();
    }

    protected function validateResponse(OperationAddress $operation, Response $response): void
    {
        $psrFactory = self::$container->get(PsrHttpFactory::class);
        $psrResponse = $psrFactory->createResponse($response);

        try {
            $this->validator->validate($operation, $psrResponse);
        } catch (ValidationFailed $e) {
            $message = $e->getMessage();

            if ($e->getPrevious() === null) {
                $this->fail($message);
            }

            if ($e->getPrevious() instanceof SchemaMismatch) {
                $breadCrumb = $e->getPrevious()->dataBreadCrumb();

                if ($breadCrumb instanceof BreadCrumb) {
                    $path = implode('.', $breadCrumb->buildChain());
                } else {
                    $path = '';
                }

                $message .= ':' . PHP_EOL . 'Path: ' . $path . PHP_EOL . 'Error: ' . $e->getPrevious()->getMessage();
            }

            static::fail($message);
        }

        $this->addToAssertionCount(1);
    }

    protected function getAccessTokenFor(User $user): string
    {
        $tokenService = static::$container->get(TokenService::class);

        return $tokenService->createToken($user);
    }
}
