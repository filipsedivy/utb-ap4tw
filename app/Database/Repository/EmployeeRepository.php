<?php

declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;
use Doctrine\ORM\EntityRepository;

final class EmployeeRepository extends EntityRepository
{
    public function findActiveByUsername(string $username): ?Employee
    {
        $entity = $this->findOneBy([
            'username' => $username,
            'active' => true
        ]);

        if ($entity instanceof Employee) {
            return $entity;
        }

        return null;
    }
}
