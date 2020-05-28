<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;

final class PostTagTest extends ContractTestCase
{
    use DatabasePrimer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabaseSchema(self::$container->get(EntityManagerInterface::class));
    }

    public function testItCreatesANewTag(): void
    {
        $this->client->request(
            'POST',
            'tags',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'name' => 'docs'
            ], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();

        $this->validateResponse(new OperationAddress('/tags', 'post'), $response);
    }
}
