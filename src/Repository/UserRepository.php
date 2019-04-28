<?php
declare(strict_types=1);

namespace App\Repository;

use App\Entity\RoomAction;
use App\Entity\User;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function resetUserGameData(User $user, RoomAction $startRoomAction): User
    {
        $user->setCurrentRoomAction($startRoomAction);
        $user->setLife(User::LIFE_FULL);
        $user->resetBlackListedRoom();
        $user->setRoomNumber(User::START_ROOM_NUMBER);
        $user->resetItems();

        $this->add($user);

        return $user;
    }

    public function addOneToRoomNumber(User $user): int
    {
        $roomNumber = $user->getRoomNumber();
        $roomNumber++;
        $user->setRoomNumber($roomNumber);

        $this->add($user);

        return $roomNumber;
    }
}
