<?php declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;

trait TRepositories
{
    final public function getEmployeeRepository(): EmployeeRepository
    {
        return $this->getRepository(Employee::class);
    }
}