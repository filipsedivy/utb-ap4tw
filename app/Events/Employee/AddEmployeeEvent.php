<?php

declare(strict_types=1);

namespace App\Events\Employee;

use App\Database\Entity\Employee;

final class AddEmployeeEvent
{
    private Employee $entity;

    public function __construct(Employee $entity)
    {
        $this->entity = $entity;
    }

    public function getEntity(): Employee
    {
        return $this->entity;
    }
}
