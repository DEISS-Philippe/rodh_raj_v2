<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use Sylius\Component\Resource\Model\ResourceInterface;

class Room implements ResourceInterface
{
    /** @var int */
    private $id;

    /** @var string */
    private $name = '';

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
}