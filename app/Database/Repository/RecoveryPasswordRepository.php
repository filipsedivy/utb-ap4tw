<?php declare(strict_types=1);

namespace App\Database\Repository;

use App\Database\Entity\RecoveryPassword;
use Doctrine\ORM\EntityRepository;
use Nette\Utils;

final class RecoveryPasswordRepository extends EntityRepository
{
    public function findByToken(string $token): ?RecoveryPassword
    {
        $now = Utils\DateTime::from('now');

        $qb = $this->createQueryBuilder('rp');
        $qb->where('rp.token = :token')
            ->andWhere('rp.expiredAt > :now')
            ->setMaxResults(1);

        $qb->setParameter('token', $token)
            ->setParameter('now', $now);

        return $qb->getQuery()->getOneOrNullResult();
    }
}