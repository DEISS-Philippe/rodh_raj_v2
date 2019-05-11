<?php
declare(strict_types=1);

namespace App\Command;

use App\Entity\Item;
use App\Entity\RoomAction;
use App\Entity\RoomAction\ChanceAction;
use App\Entity\RoomAction\Choice;
use App\Factory\ItemFactory;
use App\Factory\RoomAction\ChanceActionFactory;
use App\Factory\RoomAction\ChoiceFactory;
use App\Factory\RoomActionFactory;
use App\Repository\ItemRepository;
use App\Repository\RoomAction\ChanceActionRepository;
use App\Repository\RoomAction\ChoiceRepository;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\Yaml\Yaml;

class VanillaRoomsWarmUp extends Command
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var RoomActionRepository
     */
    private $roomActionRepository;
    /**
     * @var RoomActionFactory
     */
    private $roomActionFactory;
    /**
     * @var ChoiceFactory
     */
    private $choiceFactory;
    /**
     * @var ChanceActionFactory
     */
    private $chanceActionFactory;
    /**
     * @var ItemFactory
     */
    private $itemFactory;
    /**
     * @var ChoiceRepository
     */
    private $choiceRepository;
    /**
     * @var ChanceActionRepository
     */
    private $chanceActionRepository;
    /**
     * @var ItemRepository
     */
    private $itemRepository;

    protected $roomArray;
    protected $output;
    /** @var Choice */
    protected $choice;

    public function __construct(UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                RoomActionFactory $roomActionFactory,
                                ChoiceFactory $choiceFactory, ChanceActionFactory $chanceActionFactory,
                                ItemFactory $itemFactory,
                                ChoiceRepository $choiceRepository,
                                ChanceActionRepository $chanceActionRepository,
                                ItemRepository $itemRepository,
                                string $name = null)
    {
        parent::__construct($name);
        $this->userRepository = $userRepository;
        $this->roomActionRepository = $roomActionRepository;
        $this->roomActionFactory = $roomActionFactory;
        $this->choiceFactory = $choiceFactory;
        $this->chanceActionFactory = $chanceActionFactory;
        $this->itemFactory = $itemFactory;
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
        $output->writeln('['.date('Y-m-d H:i:s').'] Warming Vanilla Rooms');

        $roomArray = Yaml::parseFile('C:\xampp\htdocs\rodh_raj\private\rooms_data.yaml');
        $this->roomArray = $roomArray;
        $this->output = $output;

        foreach ($roomArray as $room) {
            $this->treatRoom($room);
        }

        $output->writeln('['.date('Y-m-d H:i:s').'] Warming Vanilla Rooms completed');
        return 1;
    }

    public function treatRoom(array $room): void
    {
        $this->output->writeln('['.date('Y-m-d H:i:s').'] TREATING ROOMACTION "'.$room['code'].'".', OutputInterface::VERBOSITY_VERBOSE);
        if ($this->checkExisting($room) === true) {
            $this->output->writeln('['.date('Y-m-d H:i:s').'] -- RoomAction "'.$room['code'].'" already existing.', OutputInterface::VERBOSITY_VERBOSE);
            return;
        }

        $newRoomAction = $this->treatRoomAction($room);
        if (!$newRoomAction instanceof RoomAction) {
            $this->output->writeln('<bg=red;fg=black>['.date('Y-m-d H:i:s').'] !-- Error puting RoomAction "'.$room['code'].'" in DB.</>', OutputInterface::VERBOSITY_VERBOSE);
            return;
        } else {
            $this->output->writeln('['.date('Y-m-d H:i:s').'] -- Success puting RoomAction "'.$room['code'].'" in DB.', OutputInterface::VERBOSITY_VERBOSE);
        }

        foreach ($room['choices'] as $choice) {
            $newChoice = $this->treatChoice($choice, $newRoomAction);
            $this->choice = $newChoice;

            if (!$newChoice instanceof Choice) {
                $this->output->writeln('<bg=red;fg=black>['.date('Y-m-d H:i:s').'] !---- Error puting Choice "'.$choice['text'].'" in DB.</>', OutputInterface::VERBOSITY_VERBOSE);
                return;
            } else {
                $this->output->writeln('['.date('Y-m-d H:i:s').'] ---- Success puting Choice "'.$choice['text'].'" in DB.</>', OutputInterface::VERBOSITY_VERBOSE);
            }

            if (!empty($choice['itemAction']['item'])) {
                $newItemAction = $this->itemExistOrAddInDB($choice['itemAction']['item']);
                if (!$newItemAction instanceof Item) {
                    $this->output->writeln('<bg=red;fg=black>['.date('Y-m-d H:i:s').'] !------ Error puting ItemAction with Item "'.$choice['itemAction']['item'].'" in DB.</>', OutputInterface::VERBOSITY_VERBOSE);
                    continue;
                } else {
                    $newChoice->setItemAction($newItemAction);
                    $this->output->writeln('['.date('Y-m-d H:i:s').'] ------ Success puting ItemAction with Item "'.$choice['itemAction']['item'].'" in DB.', OutputInterface::VERBOSITY_VERBOSE);
                }
            }

            if (!empty($choice['chanceAction']['chance'])
                && !empty($choice['chanceAction']['successRoomActionCode'])
                && !empty($choice['chanceAction']['failureRoomActionCode']))
            {
                $newChanceAction = $this->treatChanceAction($choice, $newChoice);
                if (!$newChanceAction instanceof ChanceAction) {
                    $this->output->writeln('<bg=red;fg=black>['.date('Y-m-d H:i:s').'] !------ Error puting ChanceAction with chance "'.$choice['chanceAction']['chance'].'" in DB.</>', OutputInterface::VERBOSITY_VERBOSE);
                    continue;
                } else {
                    $this->output->writeln('['.date('Y-m-d H:i:s').'] ------ Success puting ChanceAction with chance "'.$choice['chanceAction']['chance'].'" in DB.', OutputInterface::VERBOSITY_VERBOSE);
                }
            }
        }
    }

    public function findAndTreatRoomByCode(string $code): void
    {
        foreach ($this->roomArray as $room) {
            if ($room['code'] === $code) {
                $this->treatRoom($room);
            }
        }
    }

    public function existOrTreatRoomByCode(string $code)
    {
        /** @var RoomAction|null $roomAction */
        $existingRoomAction = $this->roomActionRepository->findOneBy(['code' => $code]);
        if ($existingRoomAction !== null){
            /** @var RoomAction $targetRoomAction */
            return $existingRoomAction;
        }
        else {
            $this->output->writeln('<bg=yellow;fg=black>['.date('Y-m-d H:i:s').'] --!-- None existing target RoomAction "'.$code.'", creating it.</>', OutputInterface::VERBOSITY_VERBOSE);

            $this->findAndTreatRoomByCode($code);
            $existingRoomAction = $this->roomActionRepository->findOneBy(['code' => $code]);
            if ($existingRoomAction instanceof RoomAction) {
                $this->output->writeln('<bg=yellow;fg=black>['.date('Y-m-d H:i:s').'] --!-- Target RoomAction created</>', OutputInterface::VERBOSITY_VERBOSE);
            }
            return $existingRoomAction;
        }
    }

    public function checkExisting(array $room): bool
    {
        $existing = $this->roomActionRepository->findOneBy(['code' => $room['code']]);
        if ($existing) {
            return true;
        }
        return false;
    }

    public function treatRoomAction(array $room): RoomAction
    {
        $newRoomAction = $this->roomActionFactory->createNewWithBasicValues($room['name'], $room['text'], $room['code']);
        if (!empty($room['looseLife'])) {
            $newRoomAction->setLooseLife(intval($room['looseLife']));
        }
        if (!empty($room['isStartRoomAction'])) {
            $newRoomAction->setIsStartRoomAction(true);
        }
        if (!empty($room['addItem'])) {
            $item = $this->itemExistOrAddInDB($room['addItem']);
            $newRoomAction->setAddItem($item);
        }

        $this->roomActionRepository->add($newRoomAction);

        return $newRoomAction;
    }

    public function itemExistOrAddInDB($itemName)
    {
        $item = $this->itemRepository->findOneBy(['name' => $itemName]);
        if (!$item instanceof Item){
            $item = $this->itemFactory->createNewWithName($itemName);
            $this->itemRepository->add($item);
        }

        return $item;
    }

    public function treatChoice(array $choice, RoomAction $newRoomAction): Choice
    {
        //null correspond à prochaine salle aléatoire
        $targetRoomAction = null;
        if (!empty($choice['target'])) {
            /** @var RoomAction|null $targetRoomAction */
            $targetRoomAction = $this->existOrTreatRoomByCode($choice['target']);
        }

        /** @var Choice $choice */
        $newChoice = $this->choiceFactory->createNewWithBasicValues($choice['text'], $newRoomAction, $targetRoomAction);
        if (!empty($choice['isBackToMenu'])) {
            $newChoice->setIsBackToMenu(true);
        }
        $this->choiceRepository->add($newChoice);

        return $newChoice;
    }

    public function treatChanceAction(array $choice, Choice $newChoice): ChanceAction
    {
        $chanceAction = $choice['chanceAction'];
        $successRoomActionCode = $chanceAction['successRoomActionCode'];
        $failureRoomActionCode = $chanceAction['failureRoomActionCode'];
        /** @var RoomAction $successRoomAction */
        $successRoomAction = $this->roomActionRepository->findOneBy(['code' => $successRoomActionCode]);
        /** @var RoomAction $failureRoomAction */
        $failureRoomAction = $this->roomActionRepository->findOneBy(['code' => $failureRoomActionCode]);

        if (!$successRoomAction instanceof RoomAction) {
            $successRoomAction = $this->existOrTreatRoomByCode($successRoomActionCode);
            if ($successRoomAction instanceof RoomAction) {
                $this->output->writeln('<bg=yellow;fg=black>['.date('Y-m-d H:i:s').'] --!-- Target RoomAction created</>', OutputInterface::VERBOSITY_VERBOSE);
            }
        }
        if (!$failureRoomAction instanceof RoomAction) {
            $failureRoomAction = $this->existOrTreatRoomByCode($failureRoomActionCode);
            if ($failureRoomAction instanceof RoomAction) {
                $this->output->writeln('<bg=yellow;fg=black>['.date('Y-m-d H:i:s').'] --!-- Target RoomAction created</>', OutputInterface::VERBOSITY_VERBOSE);
            }
        }

        if ($successRoomAction !== null && $failureRoomAction !== null) {
            /** @var ChanceAction $chanceAction */
            $chanceAction = $this->chanceActionFactory->createNewWithBasic(
                $chanceAction['chance'],
                $newChoice,
                $successRoomAction,
                $failureRoomAction
            );
            $this->choice->setChanceAction($chanceAction);
            $this->chanceActionRepository->add($chanceAction);

            return $chanceAction;
        } else {
            throw new ParameterNotFoundException('Success or Failure RoomAction not found');
        }
    }
}