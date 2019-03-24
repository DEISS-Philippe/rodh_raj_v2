<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\RoomAction;
use App\Entity\User;

class UserRepository extends \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
{
    public function resetUserGameData(User $user, RoomAction $startRoomAction)
    {
        $user->setCurrentRoomAction($startRoomAction);
        $user->setLife(User::LIFE_FULL);
        $user->resetBlackListedRoom();
        $user->setRoomNumber(User::START_ROOM_NUMBER);
        $user->resetItems();

        $this->add($user);

        return $user;
    }
}
