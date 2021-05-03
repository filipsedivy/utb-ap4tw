<?php

declare(strict_types=1);

namespace App\Components\Customer\View;

use App\Core;
use App\Database\Entity;

final class View extends Core\UI\CoreControl
{
    private bool $showArchived;

    private Entity\EntityManager $entityManager;

    public function __construct(
        Entity\EntityManager $entityManager,
        bool $showArchived = false
    ) {
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
