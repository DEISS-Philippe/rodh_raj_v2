<?php

declare(strict_types = 1);

namespace App\Event\Listener;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Event\DonjonControllerEvent;
use App\Repository\UserRepository;

final class UserListner
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {

        $this->userRepository = $userRepository;
    }

    private function handleLife(DonjonControllerEvent $event): void
    {
        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $event->getSubject();
        /** @var User $user */
        $user = $event->getUser();

        $lifeToLoose = $currentRoomAction->getLooseLife();
        if ($lifeToLoose !== null) {
            // Si la sale fait perdre de la vie, l'enlève à celle du user
            $currentUserLife = $user->getLife();
            $user->setLife($currentUserLife - $lifeToLoose);

            $this->userRepository->add($user);
        }
    }
}
