<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\RoomAction;

class RoomActionRepository extends \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
{
    public function findByCode(string $code): array
    {
        $qb = $this->createQueryBuilder('ra');

        $qb->andWhere('ra.code LIKE :code')
            ->setParameter('code', $code.'%');

        return $qb->getQuery()->getResult();
    }

    public function findEntranceRoomAction(): RoomAction
    {
        /** @var RoomAction $roomAction */
        $roomAction = $this->findOneBy(['code' => 'entree_du_donjon_1']);

        return $roomAction;
    }

    public function findBossRoomAction(): RoomAction
    {
        /** @var RoomAction $roomAction */
        $roomAction = $this->findOneBy(['code' => 'salle_du_boss_1']);

        return $roomAction;
    }
}