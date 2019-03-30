<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\RoomAction;
use Sylius\Component\Resource\Factory;

class RoomActionFactory implements Factory\FactoryInterface
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

    public function createNewWithBasicValues(string $text, ?int $looseLife = null)
    {
        /** @var RoomAction $roomAction */
        $roomAction = $this->createNew();
        $roomAction->setText($text);
        $roomAction->setLooseLife($looseLife);

        return $roomAction;
    }
}