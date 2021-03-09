<?php declare(strict_types=1);

namespace App\Services\User;

use App\Database\Entity\EntityManager;
use Nette;
use Nette\Security\IIdentity;

final class Authenticator implements Nette\Security\Authenticator
{
    private EntityManager $entityManager;

    private Nette\Security\Passwords $passwords;

    public function __construct(EntityManager $entityManager,
                                Nette\Security\Passwords $passwords)
    {
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

        $employeeToArray = [
            'username' => $employee->getUsername(),
            'name' => $employee->getName(),
            'email' => $employee->getEmail()
        ];

        return new Nette\Security\SimpleIdentity($employee->getId(), [], $employeeToArray);
    }
}