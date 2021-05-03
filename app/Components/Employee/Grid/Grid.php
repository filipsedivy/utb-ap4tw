<?php

declare(strict_types=1);

namespace App\Components\Employee\Grid;

use App\Core;
use App\Database\Entity\EntityManager;
use App\Database\Repository\EmployeeRepository;

final class Grid extends Core\UI\CoreControl
{
    private EmployeeRepository $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getEmployeeRepository();
    }

    public function beforeRender(): void
    {
        $this->template->employees = $this->repository->findAll();
    }
}
