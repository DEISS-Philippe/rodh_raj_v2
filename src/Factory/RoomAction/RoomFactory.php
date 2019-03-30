<?php
declare(strict_types=1);

namespace App\Factory\RoomAction;

use App\Entity\RoomAction\Room;
use Sylius\Component\Resource\Factory;

class RoomFactory implements Factory\FactoryInterface
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

    public function createNewWithName(string $name): Room
    {
        /** @var Room $room */
        $room = $this->createNew();
        $room->setName($name);

        return $room;
    }
}