<?php
declare(strict_types=1);

namespace App\Repository;

class RoomActionRepository extends \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
{
    public function findByCode(string $code): array
    {
        $qb = $this->createQueryBuilder('ra');

        $qb->andWhere('ra.code LIKE \':code%\'')
            ->setParameter('code', $code);

        return $qb->getQuery()->getResult();
    }
}