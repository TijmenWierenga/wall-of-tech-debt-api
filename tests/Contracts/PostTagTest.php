<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use App\Tests\DatabasePrimer;
use Doctrine\ORM\EntityManagerInterface;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
        $userRepository = static::$container->get(UserRepository::class);
        $passwordEncoder = static::$container->get(UserPasswordEncoderInterface::class);
        $user = User::new(Uuid::uuid4(), 'tijmen', '');
        $user->setPassword($passwordEncoder->encodePassword($user, '123456'));
        $userRepository->save($user);

        $token = $this->getAccessTokenFor($user);

        $this->client->request(
            'POST',
            'tags',
            [],
            [],
            [
                'CONTENT_TYPE' => 'application/json',
                'HTTP_AUTHORIZATION' => "Bearer ${token}"
            ],
            json_encode([
                'name' => 'docs'
            ], JSON_THROW_ON_ERROR)
        );

        $response = $this->client->getResponse();

        $this->validateResponse(new OperationAddress('/tags', 'post'), $response);
    }
}
