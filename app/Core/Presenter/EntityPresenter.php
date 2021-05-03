<?php

declare(strict_types = 1);

namespace App\Core\Presenter;

use App\Database\Entity\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use App\Core\Presenter\LogicException;
use function class_exists;
use App\Core\Presenter\InvalidArgumentException;

trait EntityPresenter
{

    final public function checkOneById(string $class, int $id): object
    {
        if (!$this->entityManager instanceof EntityManager) {
            throw new LogicException('Invalid EntityManager');
        }

        if (!class_exists($class)) {
            throw new InvalidArgumentException('Class not exists');
        }

        $repository = $this->entityManager->getRepository($class);

        if (!$repository instanceof ObjectRepository) {
            throw new LogicException('Repository not exists');
        }

        $entity = $repository->find($id);

        if (null === $entity) {
            $this->error('Entity not exists');
        }

        return $entity;
    }

}
