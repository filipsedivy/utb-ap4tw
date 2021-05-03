<?php

declare(strict_types = 1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;
use Doctrine\ORM\EntityRepository;

final class NoteRepository extends EntityRepository
{

	/** @return array<int, array<string, int>> */
	public function getAccessibleNotes(Employee $employee): array
	{
		$qb = $this->createQueryBuilder('note');
		$qb->select('note.id')
		->andWhere('note.private = 1 AND note.creator = :creator')
		->orWhere('note.private = 0')
		->orderBy('FIELD(note.creator, :creator)', 'DESC')
		->addOrderBy('note.created', 'DESC')
		->setParameter('creator', $employee);

		return $qb->getQuery()->getArrayResult();
	}

}
