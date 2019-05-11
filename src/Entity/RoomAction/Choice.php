<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use App\Entity\Item;
use App\Entity\RoomAction;
use Sylius\Component\Resource\Model\ResourceInterface;

class Choice implements ResourceInterface
{
    /** @var int */
    private $id;
    /** @var RoomAction */
    private $roomAction = null;
    /** @var string */
    private $text = '';
    /** @var RoomAction */
    private $targetRoomAction = null;
    /** @var Item|null */
    private $itemAction = null;
    /** @var ChanceAction|null */
    private $chanceAction = null;
    /** @var bool */
    private $isBackToMenu = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return RoomAction
     */
    public function getRoomAction(): RoomAction
    {
        return $this->roomAction;
    }

    /**
     * @param RoomAction $roomAction
     */
    public function setRoomAction(RoomAction $roomAction): void
    {
        $this->roomAction = $roomAction;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     */
    public function setText(string $text): void
    {
        $this->text = $text;
    }

    /**
     * @return RoomAction
     */
    public function getTargetRoomAction(): ?RoomAction
    {
        return $this->targetRoomAction;
    }

    /**
     * @param RoomAction $targetRoomAction
     */
    public function setTargetRoomAction(?RoomAction $targetRoomAction): void
    {
        $this->targetRoomAction = $targetRoomAction;
    }

    /**
     * @return Item|null
     */
    public function getItemAction(): ?Item
    {
        return $this->itemAction;
    }

    /**
     * @param Item|null $itemAction
     */
    public function setItemAction(?Item $itemAction): void
    {
        $this->itemAction = $itemAction;
    }

    /**
     * @return ChanceAction|null
     */
    public function getChanceAction(): ?ChanceAction
    {
        return $this->chanceAction;
    }

    /**
     * @param ChanceAction|null $chanceAction
     */
    public function setChanceAction(?ChanceAction $chanceAction): void
    {
        $this->chanceAction = $chanceAction;
    }

    /**
     * @return bool
     */
    public function isBackToMenu(): bool
    {
        return $this->isBackToMenu;
    }

    /**
     * @param bool $isBackToMenu
     */
    public function setIsBackToMenu(bool $isBackToMenu): void
    {
        $this->isBackToMenu = $isBackToMenu;
    }
}