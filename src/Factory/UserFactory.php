<?php
declare(strict_types=1);

namespace App\Factory;

use Sylius\Component\Resource\Factory;

class UserFactory implements Factory\FactoryInterface
{
    /**
     * @var Factory\FactoryInterface
     */
    private $factory;

    public function __construct(Factory\FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createNew()
    {
        return $this->factory->createNew();
    }

    public function createNewBasicUser()
    {
        $user = $this->createNew();
        $user->setLife(3);
        $user->setRoomNumber(1);

        return $user;
    }
}
