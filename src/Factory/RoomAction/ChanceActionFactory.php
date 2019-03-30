<?php
declare(strict_types=1);

namespace App\Factory\RoomAction;

use App\Entity\RoomAction;
use App\Entity\RoomAction\ChanceAction;
use App\Entity\RoomAction\Choice;
use Sylius\Component\Resource\Factory;

class ChanceActionFactory implements Factory\FactoryInterface
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

    public function createNewWithBasic(int $chance, Choice $choice, RoomAction $successRoomAction,
                                       RoomAction $failureRoomAction): ChanceAction
    {
        /** @var ChanceAction $chanceAction */
        $chanceAction = $this->createNew();
        $chanceAction->setChance($chance);
        $chanceAction->setParentChoice($choice);
        $chanceAction->setSuccessRoomAction($successRoomAction);
        $chanceAction->setFailRoomAction($failureRoomAction);

        return $chanceAction;
    }
}