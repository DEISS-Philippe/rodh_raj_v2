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

    public function generateBindingWithCode(string $roomCode, User $user)
    {
        /** @var RoomAction[] $roomActionWithCode */
        $roomActionsWithCode = $this->roomActionRepository->findByCode($roomCode);

        /** @var RoomAction $roomActionWithCode */
        foreach($roomActionsWithCode as $roomActionWithCode) {
            $binder = $this->binderFactory->createNewWithToken($user, $roomActionWithCode);
            $this->binderRepository->add($binder);
        }
    }

    public function generateBindings(RoomAction $roomAction): void
    {
        /** @var User $user */
        $user = $this->tokenStorage->getToken()->getUser();

        $this->generateBindingForRoom($roomAction, $user);
        $this->generateBindingForMandatoryRoom($user);
    }

    public function generateBindingForRoom(RoomAction $roomAction, User $user): void
    {
        $roomActionCode = $roomAction->getCode();
        $explodedRoomCode = explode('_', $roomActionCode);

        $roomCode = '';
        for ($i = 0; $i < sizeof($explodedRoomCode)-1; $i++) {
            $roomCode .= $explodedRoomCode[$i].'_';
        }

        $this->generateBindingWithCode($roomCode, $user);
    }

    public function generateBindingForMandatoryRoom(User $user): void
    {
        $this->generateBindingForEntrance($user);
        $this->generateBindingForBoss($user);
    }

    public function generateBindingForEntrance(User $user): void
    {
        $roomCode = 'entree_du_donjon_';

        $this->generateBindingWithCode($roomCode, $user);
    }
    public function generateBindingForBoss(User $user): void
    {
        $roomCode = 'salle_du_boss_';

        $this->generateBindingWithCode($roomCode, $user);
    }
}
