<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use App\Entity\Item;
use Sylius\Component\Resource\Model\ResourceInterface;

class ItemAction implements ResourceInterface
{
    protected static $CONSTANT_GIVE = 0;
    protected static $CONSTANT_ASK = 1;

    /** @var int */
    private $id;
    /** @var Item */
    private $item = null;
    /** @var bool */
    private $action = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }

    /**
     * @param Item $item
     */
    public function setItem(Item $item): void
    {
        $this->item = $item;
    }

    /**
     * @return bool
     */
    public function isAction(): bool
    {
        return $this->action;
    }

    /**
     * @param bool $action
     */
    public function setAction(bool $action): void
    {
        $this->action = $action;
    }

}