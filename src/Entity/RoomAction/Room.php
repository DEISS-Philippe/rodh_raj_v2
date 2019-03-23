<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use App\Entity\RoomAction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Component\Resource\Model\ResourceInterface;

class Room implements ResourceInterface
{
    /** @var int */
    private $id;

    /** @var string */
    private $name = '';

    /** @var RoomAction[] */
    private $roomActions;

    public function __construct()
    {
        $this->roomActions = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getRoomActions(): Collection
    {
        return $this->roomActions;
    }
}