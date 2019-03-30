<?php

namespace App\Fixture;

use App\Services\RoomToDatabaseSenderService;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Yaml\Yaml;

class RoomFixtures extends AbstractFixture implements FixtureInterface
{
    /**
     * @var RoomToDatabaseSenderService
     */
    private $databaseSenderService;

    public function __construct(RoomToDatabaseSenderService $databaseSenderService)
    {

        $this->databaseSenderService = $databaseSenderService;
    }

    public function load(array $options): void
    {
        // TODO au passage sur un vrai serveur changer le lien
        $roomsArray = Yaml::parseFile('C:\xampp\htdocs\rodh_raj\private\rooms_data.yaml');

        $this->databaseSenderService->RoomArraySender($roomsArray);
    }

    public function getName():string
    {
        return 'room';
    }
}
