<?php
declare(strict_types=1);

namespace App\Factory\RoomAction;

use App\Entity\RoomAction;
use Sylius\Component\Resource\Factory;

class ChoiceFactory implements Factory\FactoryInterface
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

    public function createNewWithBasicValues(string $text, RoomAction $parentRoomAction, RoomAction $targetRoomAction = null)
    {
        /** @var RoomAction\Choice $choice */
        $choice = $this->createNew();
        $choice->setTargetRoomAction($targetRoomAction);
        $choice->setRoomAction($parentRoomAction);
        $choice->setText($text);

        return $choice;
    }
}