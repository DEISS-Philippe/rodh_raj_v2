<?php
declare(strict_types=1);

namespace App\Factory\RoomAction;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Services\Generator\RoomTokenGenerator;
use Sylius\Component\Resource\Factory;

class BinderFactory implements Factory\FactoryInterface
{
    /**
     * @var Factory\FactoryInterface
     */
    private $factory;
    /**
     * @var RoomTokenGenerator
     */
    private $generator;

    public function __construct(Factory\FactoryInterface $factory, RoomTokenGenerator $generator)
    {
        $this->factory = $factory;
        $this->generator = $generator;
    }

    public function createNew()
    {
        return $this->factory->createNew();
    }

    public function createNewWithToken(User $user, RoomAction $roomAction): RoomAction\Binder
    {
        /** @var RoomAction\Binder $binder */
        $binder = $this->createNew();
        $binder->setBinderToken($this->generator->generateRandomToken());
        $binder->setUser($user);
        $binder->setRoomAction($roomAction);

        return $binder;
    }
}