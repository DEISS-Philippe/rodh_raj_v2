<?php
declare(strict_types=1);

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class Item implements ResourceInterface
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
     * @param string $title
     */
    public function setName($name)
    {
        $this->name = $name;
    }
}