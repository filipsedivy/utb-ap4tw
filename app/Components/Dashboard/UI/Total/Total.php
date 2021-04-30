<?php declare(strict_types=1);

namespace App\Components\Dashboard\UI\Total;

use App\Components\Dashboard;
use App\Database\Entity\EntityManager;
use Doctrine\ORM\EntityRepository;

final class Total extends Dashboard\UI\BaseControl
{
    private EntityRepository $repository;

    public function __construct(string $classObject, EntityManager $entityManager)
    {
        $repo = $entityManager->getRepository($classObject);
        assert($repo instanceof EntityRepository);
        $this->repository = $repo;
    }

    public function beforeRender(): void
    {
        parent::beforeRender();
        $this->template->result = $this->repository->count([]);
    }
}