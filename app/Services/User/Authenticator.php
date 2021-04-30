<?php

declare(strict_types=1);

namespace App\Services\User;

use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use Nette;
use Nette\Security\IIdentity;

final class Authenticator implements Nette\Security\Authenticator, Nette\Security\IdentityHandler
{
    private EntityManager $entityManager;

    private Nette\Security\Passwords $passwords;

    public function __construct(
        EntityManager $entityManager,
        Nette\Security\Passwords $passwords
    ) {
        $this->entityManager = $entityManager;
        $this->passwords = $passwords;
    }

    public function authenticate(string $user, string $password): IIdentity
    {
        $repository = $this->entityManager->getEmployeeRepository();
        $employee = $repository->findActiveByUsername($user);

        if ($employee === null) {
            throw new Nette\Security\AuthenticationException('User not found');
        }

        if (!$this->passwords->verify($password, $employee->getPassword())) {
            throw new Nette\Security\AuthenticationException('Bad password');
        }

        return $this->createIdentity($employee);
    }


    public function wakeupIdentity(IIdentity $identity): ?IIdentity
    {
        $employee = $this->entityManager->getEmployeeRepository()->findOneBy([
            'authToken' => $identity->getId()
        ]);

        if (!$employee instanceof Employee) {
            return null;
        }

        return $this->createIdentity($employee);
    }

    public function sleepIdentity(IIdentity $identity): IIdentity
    {
        $token = Nette\Utils\Random::generate(13);
        $employee = $this->entityManager->getEmployeeRepository()->find($identity->getId());
        assert($employee instanceof Employee);

        $employee->setAuthToken($token);
        $this->entityManager->flush($employee);

        return new Nette\Security\SimpleIdentity($token);
    }

    private function createIdentity(Employee $employee): Nette\Security\SimpleIdentity
    {
        $fileSystemRepository = $this->entityManager->getFileSystemRepository();
        $usageDiskSpace = $fileSystemRepository->getUsageByUser($employee);

        return new Nette\Security\SimpleIdentity($employee->id, [], [
            'username' => $employee->username,
            'name' => $employee->name,
            'email' => $employee->email,
            'disk' => [
                'space' => $employee->diskSpace,
                'usage' => $usageDiskSpace,
                'free' => $employee->diskSpace - $usageDiskSpace
            ]
        ]);
    }
}
