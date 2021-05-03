<?php

declare(strict_types = 1);

namespace App\Database\Repository;

use App\Database\Entity\Employee;
use Doctrine\ORM\EntityRepository;

final class FileSystemRepository extends EntityRepository
{

	public function getUsageByUser(Employee $employee): int
	{
		$qb = $this->createQueryBuilder('fs');
		$qb->select('SUM(fs.size)')
		->where('fs.user = :user');
		$qb->setParameter('user', $employee);

		return (int)$qb->getQuery()->getSingleScalarResult();
	}

}
