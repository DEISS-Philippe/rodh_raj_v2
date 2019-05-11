<?php

namespace App\Services\Finder;

use App\Entity\RoomAction;
use App\Entity\RoomAction\Binder;
use App\Repository\RoomAction\BinderRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RoomActionFinder
{
    /**
     * @var BinderRepository
     */
    private $binderRepository;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(BinderRepository $binderRepository, TokenStorageInterface $tokenStorage)
    {

        $this->binderRepository = $binderRepository;
        $this->tokenStorage = $tokenStorage;
    }

    public function findRoomActionByUserBinderToken(string $binderToken): RoomAction
    {
        $user = $this->tokenStorage->getToken()->getUser();

        /** @var Binder $binder */
        $binder = $this->binderRepository->findOneBy(['user' => $user, 'binderToken' => $binderToken]);
        $roomAction = $binder->getRoomAction();

        return $roomAction;
    }

    public function findBinderTokenByRoomAction(RoomAction $roomAction): string
    {
        $user = $this->tokenStorage->getToken()->getUser();

        /** @var Binder $binder */
        $binder = $this->binderRepository->findOneBy(['user' => $user, 'roomAction' => $roomAction]);
        $binderToken = $binder->getBinderToken();

        return $binderToken;
    }
}
