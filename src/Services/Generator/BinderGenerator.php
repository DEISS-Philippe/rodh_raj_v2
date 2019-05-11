<?php

declare(strict_types=1);

namespace App\Services\Generator;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Factory\RoomAction\BinderFactory;
use App\Repository\RoomAction\BinderRepository;
use App\Repository\RoomActionRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class BinderGenerator
{
    private $binderRepository;
    private $roomActionRepository;
    private $tokenStorage;
    private $binderFactory;

    public function __construct(BinderRepository $binderRepository, RoomActionRepository $roomActionRepository,
                                TokenStorageInterface $tokenStorage, BinderFactory $binderFactory)
    {
        $this->binderRepository = $binderRepository;
        $this->roomActionRepository = $roomActionRepository;
        $this->tokenStorage = $tokenStorage;
        $this->binderFactory = $binderFactory;
    }

    public function generateBindingForRoom(RoomAction $roomAction)
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        //On retire les anciens liens pour le user
        $this->binderRepository->removeFormerBinderForUser($user);

        $roomActionCode = $roomAction->getCode();
        $explodedRoomCode = explode('_', $roomActionCode);

        $roomCode = '';
        for ($i = 0; sizeof($explodedRoomCode)-1; $i++) {
            $roomCode .= $explodedRoomCode[$i].'_';
        }

        dump($roomCode);

        /** @var RoomAction[] $roomActionWithCode */
        $roomActionsWithCode = $this->roomActionRepository->findByCode($roomCode);

        /** @var RoomAction $roomActionWithCode */
        foreach($roomActionsWithCode as $roomActionWithCode) {
            $binder = $this->binderFactory->createNewWithToken($user, $roomActionWithCode);
            $this->binderRepository->add($binder);
        }
    }
}
