<?php declare(strict_types=1);

namespace App\Database\Repository;

use Doctrine\ORM\EntityRepository;

final class NoteRepository extends EntityRepository
{
    /** @return array<int, array<string, int>> */
    public function getAccessibleNotes(): array
    {
        $qb = $this->createQueryBuilder('note');
        $qb->select('note.id');
        return $qb->getQuery()->getArrayResult();
    }
}