<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;

class User implements ResourceInterface
{
    /** @var int */
    private $id;
    /** @var string */
    private $login = '';
    /** @var string|null */
    private $class;
    /** @var int */
    private $life = 3;
    /** @var RoomAction[]|null */
    private $blackListedRooms = null;
    /** @var Item[]|null */
    private $items = null;
    /** @var RoomAction|null */
    private $currentRoomAction = null;
    /** @var int */
    private $roomNumber = 1;

    public function __construct()
    {
        $this->blackListedRooms = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @return string|null
     */
    public function getClass(): ?string
    {
        return $this->class;
    }

    /**
     * @param string|null $class
     */
    public function setClass(?string $class): void
    {
        $this->class = $class;
    }

    /**
     * @return int
     */
    public function getLife(): int
    {
        return $this->life;
    }

    /**
     * @param int $life
     */
    public function setLife(int $life): void
    {
        $this->life = $life;
    }

    /**
     * @return RoomAction|null
     */
    public function getCurrentRoomAction(): ?RoomAction
    {
        return $this->currentRoomAction;
    }

    /**
     * @param RoomAction $currentRoomAction
     */
    public function setCurrentRoomAction(RoomAction $currentRoomAction): void
    {
        $this->currentRoomAction = $currentRoomAction;
    }

    /**
     * @return int
     */
    public function getRoomNumber(): int
    {
        return $this->roomNumber;
    }

    /**
     * @param int $roomNumber
     */
    public function setRoomNumber(int $roomNumber): void
    {
        $this->roomNumber = $roomNumber;
    }

    /**
     * @return RoomAction[]|null
     */
    public function getBlackListedRooms(): Collection
    {
        return $this->blackListedRooms;
    }

    /**
     * @param RoomAction[]|null $blackListedRooms
     */
    public function addBlackListedRoom(RoomAction $blackListedRoom): void
    {
        if (!$this->blackListedRooms->contains($blackListedRoom)) {
            $this->blackListedRooms->add($blackListedRoom);
        }
    }
    /**
     * @param RoomAction[]|null $blackListedRooms
     */
    public function removeBlackListedRoom(RoomAction $blackListedRoom): void
    {
        if ($this->blackListedRooms->contains($blackListedRoom)) {
            $this->blackListedRooms->removeElement($blackListedRoom);
        }
    }

    /**
     * @return Item[]|null
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    /**
     * @param Item[]|null $blackListedRooms
     */
    public function addItem(Item $item): void
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
        }
    }
    /**
     * @param Item[]|null $blackListedRooms
     */
    public function removeItem(Item $item): void
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
        }
    }

}