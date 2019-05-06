<?php

namespace App\Services\Generator;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;

class NextRoomGenerator
{
    private $userRepository;
    private $roomActionRepository;

    public function __construct(UserRepository $userRepository, RoomActionRepository $roomActionRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomActionRepository = $roomActionRepository;
    }

    public function generateNextRoom(User $user): RoomAction
    {
        //Génère la possible RoomActions à venir
        $blackListedRoomActions = $user->getBlackListedRooms();

        $availableNextRoomActions = $this->roomActionRepository->findBy(['isStartRoomAction' => true]);
        $availableNextRoomActions = new ArrayCollection($availableNextRoomActions);

        /** @var RoomAction $roomAction */
        foreach ($availableNextRoomActions as $roomAction) {
            if($roomAction->getCode() === 'entree_du_donjon_1' || $roomAction->getCode() === 'salle_du_boss_1') {
                $availableNextRoomActions->removeElement($roomAction);
            }
            if ($blackListedRoomActions->contains($roomAction)) {
                $availableNextRoomActions->removeElement($roomAction);
            }
        }
        $availableNextRoomActions = $availableNextRoomActions->toArray();
        $availableNextRoomActions = array_values($availableNextRoomActions);
        $rand = rand(0, (sizeof($availableNextRoomActions) - 1));

        /** @var RoomAction $nextRoomAction */
        $nextRoomAction = $availableNextRoomActions[$rand];

        return $nextRoomAction;
    }
}
