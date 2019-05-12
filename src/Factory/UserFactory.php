<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\User;
use App\Repository\RoomActionRepository;
use Sylius\Component\Resource\Factory;

class UserFactory implements Factory\FactoryInterface
{
    /**
     * @var Factory\FactoryInterface
     */
    private $factory;
    /**
     * @var RoomActionRepository
     */
    private $roomActionRepository;

    public function __construct(Factory\FactoryInterface $factory, RoomActionRepository $roomActionRepository)
    {
        $this->factory = $factory;
        $this->roomActionRepository = $roomActionRepository;
    }

    public function createNew()
    {
        /** @var User $user */
        $user = $this->factory->createNew();

        $firstRoomAction = $this->roomActionRepository->findEntranceRoomAction();
        $user->setCurrentRoomAction($firstRoomAction);

        return $user;
    }

    public function createNewBasicUser()
    {
        $user = $this->createNew();
        $user->setLife(3);
        $user->setRoomNumber(1);

        return $user;
    }
}
