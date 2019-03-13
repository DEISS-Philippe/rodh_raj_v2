<?php
declare(strict_types=1);

namespace App\Entity;

use App\Entity\RoomAction\Choice;
use App\Entity\RoomAction\Room;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;

class RoomAction implements ResourceInterface
{
    /** @var int */
    private $id;
    /** @var Room */
    private $room = '0';
    /** @var string */
    private $text = '';
    /** @var Choice[] */
    private $choices = null;
    /** @var string|null */
    private $action = null;
    /** @var int */
    private $upVotes = 0;
    /** @var int */
    private $downVotes = 0;
    /** @var bool */
    private $isStartRoomAction = false;
    /** @var bool */
    private $isCustomRoomAction = false;

    public function __construct()
    {
        $this->choices = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Room
     */
    public function getRoom(): Room
    {
        return $this->room;
    }

    /**
     * @param Room $room
     */
    public function setRoom(Room $room): void
    {
        $this->room = $room;
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
     * @return Choice
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): void
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
        }
    }

    public function removeChoice(Choice $choice): void
    {
        if ($this->choices->contains($choice)) {
            $this->choices->removeElement($choice);
        }
    }

    /**
     * @return string|null
     */
    public function getAction(): ?string
    {
        return $this->action;
    }

    /**
     * @param string|null $action
     */
    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    /**
     * @return int
     */
    public function getUpVotes(): int
    {
        return $this->upVotes;
    }

    /**
     * @param int $upVotes
     */
    public function setUpVotes(int $upVotes): void
    {
        $this->upVotes = $upVotes;
    }

    /**
     * @return int
     */
    public function getDownVotes(): int
    {
        return $this->downVotes;
    }

    /**
     * @param int $downVotes
     */
    public function setDownVotes(int $downVotes): void
    {
        $this->downVotes = $downVotes;
    }

    /**
     * @return bool
     */
    public function isStartRoomAction(): bool
    {
        return $this->isStartRoomAction;
    }

    /**
     * @param bool $isStartRoomAction
     */
    public function setIsStartRoomAction(bool $isStartRoomAction): void
    {
        $this->isStartRoomAction = $isStartRoomAction;
    }

    /**
     * @return bool
     */
    public function isCustomRoomAction(): bool
    {
        return $this->isCustomRoomAction;
    }

    /**
     * @param bool $isCustomRoomAction
     */
    public function setIsCustomRoomAction(bool $isCustomRoomAction): void
    {
        $this->isCustomRoomAction = $isCustomRoomAction;
    }
}