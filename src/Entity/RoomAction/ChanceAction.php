<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use App\Entity\RoomAction;
use Sylius\Component\Resource\Model\ResourceInterface;

class ChanceAction implements ResourceInterface
{
    /** @var int */
    private $id;
    /** @var Choice */
    private $parentChoice = null;
    /** @var int */
    private $chance = 0;
    /** @var RoomAction */
    private $successRoomAction = null;
    /** @var RoomAction */
    private $failRoomAction = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Choice
     */
    public function getParentChoice(): Choice
    {
        return $this->parentChoice;
    }

    /**
     * @param Choice $parentChoice
     */
    public function setParentChoice(Choice $parentChoice): void
    {
        $this->parentChoice = $parentChoice;
    }

    /**
     * @return int
     */
    public function getChance(): int
    {
        return $this->chance;
    }

    /**
     * @param int $chance
     */
    public function setChance(int $chance): void
    {
        $this->chance = $chance;
    }

    /**
     * @return RoomAction
     */
    public function getSuccessRoomAction(): RoomAction
    {
        return $this->successRoomAction;
    }

    /**
     * @param RoomAction $successRoomAction
     */
    public function setSuccessRoomAction(RoomAction $successRoomAction): void
    {
        $this->successRoomAction = $successRoomAction;
    }

    /**
     * @return RoomAction
     */
    public function getFailRoomAction(): RoomAction
    {
        return $this->failRoomAction;
    }

    /**
     * @param RoomAction $failRoomAction
     */
    public function setFailRoomAction(RoomAction $failRoomAction): void
    {
        $this->failRoomAction = $failRoomAction;
    }

}