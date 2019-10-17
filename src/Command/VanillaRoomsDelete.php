<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\RoomAction;
use App\Repository\ItemRepository;
use App\Repository\RoomAction\ChanceActionRepository;
use App\Repository\RoomAction\ChoiceRepository;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VanillaRoomsDelete extends Command
{
    private $userRepository;
    private $roomActionRepository;
    private $choiceRepository;
    private $chanceActionRepository;
    private $itemRepository;

    public function __construct(UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                ChoiceRepository $choiceRepository,
                                ChanceActionRepository $chanceActionRepository,
                                ItemRepository $itemRepository,
                                string $name = null)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->roomActionRepository = $roomActionRepository;
        $this->choiceRepository = $choiceRepository;
        $this->chanceActionRepository = $chanceActionRepository;
        $this->itemRepository = $itemRepository;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Fill the DB with vanilla rooms.')
            ->setHelp('Get Vanilla Rooms from yaml file and update them in the DB.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('['.date('Y-m-d H:i:s').'] Deleting Vanilla Rooms');

        $this->output = $output;

        /** @var RoomAction[] $roomsToDelete */
        $roomsToDelete = $this->roomActionRepository->findBy(['createdBy' => null]);

        /** @var RoomAction $room */
        foreach ($roomsToDelete as $room) {
            $this->roomActionRepository->remove($room);
        }

        $output->writeln('['.date('Y-m-d H:i:s').'] Warming Vanilla Rooms completed');
        return 1;
    }
}