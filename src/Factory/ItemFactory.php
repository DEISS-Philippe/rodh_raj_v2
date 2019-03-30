<?php
declare(strict_types=1);

namespace App\Factory;

use App\Entity\Item;
use Sylius\Component\Resource\Factory;

class ItemFactory implements Factory\FactoryInterface
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

    public function createNewWithName(string $name)
    {
        /** @var Item $item */
        $item = $this->factory->createNew();
        $item->setName($name);

        return $item;
    }
}