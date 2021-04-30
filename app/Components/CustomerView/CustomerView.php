<?php

declare(strict_types=1);

namespace App\Components\CustomerView;

use App\Core\UI\CoreControl;
use App\Database\Entity\EntityManager;

final class CustomerView extends CoreControl
{
    private bool $showArchived;

    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager, bool $showArchived = false)
    {
        $this->showArchived = $showArchived;
        $this->entityManager = $entityManager;
    }

    public function beforeRender(): void
    {
        $result = $this->entityManager->getCustomerRepository()->findBy([
            'archived' => $this->showArchived
        ], ['createdAt' => 'DESC']);

        $this->template->customers = $result;
    }
}
