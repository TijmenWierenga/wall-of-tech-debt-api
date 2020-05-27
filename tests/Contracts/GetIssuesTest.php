<?php

declare(strict_types=1);

namespace App\Tests\Contracts;

use App\Controller\Issues\IssueController;
use App\Domain\Issues\Issue;
use App\Domain\Issues\IssueRepository;
use App\Domain\Security\User;
use App\Domain\Security\UserRepository;
use DateTimeImmutable;
use League\OpenAPIValidation\PSR7\OperationAddress;
use Ramsey\Uuid\Uuid;

final class GetIssuesTest extends ContractTestCase
{
    public function testItReturnsAListOfIssues(): void
    {
        static::bootKernel();

        $userRepository = self::$container->get(UserRepository::class);
        $user = User::new(Uuid::uuid4(), 'tijmen', 'fake-password');
        $userRepository->save($user);
        $issueRepository = self::$container->get(IssueRepository::class);
        $issue = new Issue(Uuid::uuid4(), 'Missing documentation', $user->getId(), new DateTimeImmutable());
        $issueRepository->save($issue);

        $issueController = self::$container->get(IssueController::class);
        $response = $issueController->index();

        $this->validateResponse(new OperationAddress('/issues', 'get'), $response);
    }
}
