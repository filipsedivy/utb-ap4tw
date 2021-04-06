<?php declare(strict_types=1);

namespace App\Services\User;

use App\Database\Entity\Employee;
use App\Database\Entity\EntityManager;
use Doctrine\ORM\EntityNotFoundException;
use Nette\Security\User;

final class IdentityRefresher
{
    private User $user;

    private EntityManager $entityManager;

    public function __construct(User $user, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->user = $user;
    }

    public function update(): void
    {
        if ($this->user->isLoggedIn()) {
            $this->updateIdentity();
        }
    }

    private function updateIdentity(): void
    {
        $employee = $this->entityManager->getEmployeeRepository()->find($this->user->id);

        if (!$employee instanceof Employee) {
            throw EntityNotFoundException::fromClassNameAndIdentifier(Employee::class, [$this->user->id]);
        }

        $identity = $this->user->identity;
        foreach (array_keys($identity->getData()) as $key) {
            $propertyName = 'get' . $key;
            if (method_exists($employee, $propertyName)) {
                $val = $employee->{$propertyName}();
                $identity->{$key} = $val;
            }
        }
    }
}