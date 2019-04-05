<?php

namespace App\Services\Sender;

use App\Entity\Item;
use App\Entity\RoomAction;
use App\Entity\RoomAction\ChanceAction;
use App\Entity\RoomAction\Choice;
use App\Entity\RoomAction\ItemAction;
use App\Factory\ItemFactory;
use App\Factory\RoomAction\ChanceActionFactory;
use App\Factory\RoomAction\ChoiceFactory;
use App\Factory\RoomAction\ItemActionFactory;
use App\Factory\RoomActionFactory;
use App\Repository\ItemRepository;
use App\Repository\RoomAction\ChanceActionRepository;
use App\Repository\RoomAction\ChoiceRepository;
use App\Repository\RoomAction\ItemActionRepository;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;

class RoomToDatabaseSenderService
{
    private $userRepository;
    private $roomActionRepository;
    private $roomActionFactory;
    private $choiceFactory;
    private $chanceActionFactory;
    private $itemActionFactory;
    private $itemFactory;
    private $choiceRepository;
    private $chanceActionRepository;
    private $itemActionRepository;
    private $itemRepository;

    protected $roomArray;

    public function __construct(UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                RoomActionFactory $roomActionFactory,
                                ChoiceFactory $choiceFactory, ChanceActionFactory $chanceActionFactory,
                                ItemActionFactory $itemActionFactory, ItemFactory $itemFactory,
                                ChoiceRepository $choiceRepository,
                                ChanceActionRepository $chanceActionRepository,
                                ItemActionRepository $itemActionRepository, ItemRepository $itemRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomActionRepository = $roomActionRepository;
        $this->roomActionFactory = $roomActionFactory;
        $this->choiceFactory = $choiceFactory;
        $this->chanceActionFactory = $chanceActionFactory;
        $this->itemActionFactory = $itemActionFactory;
        $this->itemFactory = $itemFactory;
        $this->choiceRepository = $choiceRepository;
        $this->chanceActionRepository = $chanceActionRepository;
        $this->itemActionRepository = $itemActionRepository;
        $this->itemRepository = $itemRepository;
    }

    public function RoomArraySender(array $roomArray): void
    {
        $this->roomArray = $roomArray;

        foreach ($roomArray as $room) {
            $this->treatRoom($room);
        }
    }

    public function treatRoom(array $room): void
    {
        if ($this->checkExisting($room) === true) {
            return;
        }

        $newRoomAction = $this->treatRoomAction($room);

        foreach ($room['choices'] as $choice) {
            $newChoice = $this->treatChoice($choice, $newRoomAction);

            if (!empty($choice['itemAction']['item']) && !empty($choice['itemAction']['action'])) {
                $this->treatItemAction($choice);
            }

            if (!empty($choice['chanceAction']['chance'])
                && !empty($choice['chanceAction']['successRoomActionCode'])
                && !empty($choice['chanceAction']['failureRoomActionCode']))
            {
                $this->treatChanceAction($choice, $newChoice);
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
            $this->findAndTreatRoomByCode($code);
            $existingRoomAction = $this->roomActionRepository->findOneBy(['code' => $code]);
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
            $newRoomAction->setLooseLife($room['loosLife']);
        }
        if (!empty($room['isStartRoomAction'])) {
            $newRoomAction->setIsStartRoomAction(true);
        }

        $this->roomActionRepository->add($newRoomAction);
        
        return $newRoomAction;
    }

    public function treatChoice(array $choice, RoomAction $newRoomAction): Choice
    {
        //null correspond à prochaine salle aléatoire
        $targetRoomAction = null;
        if (!empty($choice['target'])) {
            /** @var RoomAction|null $roomAction */
            $this->existOrTreatRoomByCode($choice['target']);
        }

        /** @var Choice $choice */
        $newChoice = $this->choiceFactory->createNewWithBasicValues($choice['text'], $newRoomAction, $targetRoomAction);
        if (!empty($choice['isBackToMenu'])) {
            $newChoice->setIsBackToMenu(true);
        }
        $this->choiceRepository->add($newChoice);

        return $newChoice;
    }

    public function treatItemAction(array $choice)
    {
        $itemName = $choice['itemAction']['item'];
        $action = intval($choice['itemAction']['action']);

        $item = $this->itemRepository->findOneBy(['name' => $itemName]);
        if (!$item instanceof Item){
            $item = $this->itemFactory->createNewWithName($itemName);
            $this->itemRepository->add($item);
        }

        /** @var ItemAction $itemAction */
        $itemAction = $this->itemActionFactory->createNew();
        $itemAction->setAction($action);
        $itemAction->setItem($item);
        $this->itemActionRepository->add($itemAction);

        return $itemAction;
    }

    public function treatChanceAction(array $choice, Choice $newChoice): ChanceAction
    {
        $chanceAction = $choice['chanceAction'];
        $successRoomActionCode = $chanceAction['successRoomActionCode'];
        $failureRoomAction = $chanceAction['failureRoomActionCode'];
        /** @var RoomAction $successRoomAction */
        $successRoomAction = $this->roomActionRepository->findOneBy(['code' => $successRoomActionCode]);
        /** @var RoomAction $failureRoomAction */
        $failureRoomAction = $this->roomActionRepository->findOneBy(['code' => $failureRoomAction]);

        if (!$successRoomAction instanceof RoomAction) {
            $successRoomAction = $this->existOrTreatRoomByCode($successRoomActionCode);
        }
        if (!$failureRoomAction instanceof RoomAction) {
            $failureRoomAction = $this->existOrTreatRoomByCode($failureRoomAction);
        }

        if ($successRoomAction !== null && $failureRoomAction !== null) {
            /** @var ChanceAction $chanceAction */
            $chanceAction = $this->chanceActionFactory->createNewWithBasic(
                $chanceAction['chance'],
                $newChoice,
                $successRoomAction,
                $failureRoomAction
            );
            $this->chanceActionRepository->add($chanceAction);

            return $chanceAction;
        } else {
            throw new ParameterNotFoundException('Success or Failure RoomAction not found');
        }
    }
}
