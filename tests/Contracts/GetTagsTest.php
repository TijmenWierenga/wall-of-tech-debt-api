<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Domain\Issues\Tag;
use App\Domain\Issues\TagRepository;
use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Ramsey\Uuid\Uuid;

final class GetTagsTest extends ContractTestCase
{
    use DatabasePrimer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->prepareDatabaseSchema(self::$container->get(EntityManagerInterface::class));
    }

    public function testItReturnsAllTags(): void
    {
        $userRepository = self::$container->get(UserRepository::class);
        $user = User::new(Uuid::uuid4(), 'tijmen', 'fake-password');
        $user->setFirstName('Tijmen');
        $user->setLastName('Wierenga');
        $userRepository->save($user);

        $tagRepository = self::$container->get(TagRepository::class);
        $tag = new Tag(Uuid::uuid4(), 'testing');
        $tagRepository->save($tag);

        $token = $this->getAccessTokenFor($user);

        $this->client->request(
            'GET',
            'tags',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer ${token}"
            ],
        );

        $response = $this->client->getResponse();

        $this->validateResponse(new OperationAddress('/tags', 'get'), $response);
    }
}
