<?php
declare(strict_types=1);

namespace App\Factory\RoomAction;

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
}