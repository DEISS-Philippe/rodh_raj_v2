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

        foreach ($roomArray as $room) {
            $newRoomAction = $this->roomActionFactory->createNewWithBasicValues($room['name'], $room['text']);
            if (!empty($room['loosLife'])) {
                $newRoomAction->setLooseLife($room['loosLife']);
            }
            if (!empty($room['isStartRoomAction'])) {
                $newRoomAction->setIsStartRoomAction(true);
            }
            if (!empty($room['isEndOfRoom'])) {
                $newRoomAction->setIsEndOfRoom(true);
            }

            $this->roomActionRepository->add($newRoomAction);

            foreach ($room['choices'] as $choice) {
                $targetRoomAction = null;
                if (!empty($room['choices']['target'])) {
                    $targetRoomAction = $room['choices']['target'];
                }

                /** @var Choice $choice */
                $newChoice = $this->choiceFactory->createNewWithBasicValues($choice['text'], $newRoomAction, $targetRoomAction);
                $this->choiceRepository->add($newChoice);

                if (!empty($choice['itemAction']['item']) && !empty($choice['itemAction']['action'])) {
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
                }

                if (!empty($choice['chanceAction']['chance'])
                    && !empty($choice['chanceAction']['successRoomActionTitle'])
                    && !empty($choice['chanceAction']['failureRoomActionTitle']))
                {
                    $chanceAction = $choice['chanceAction'];
                    /** @var RoomAction $successRoomAction */
                    $successRoomAction = $this->roomActionRepository->findOneBy(['name' => $chanceAction['successRoomActionTitle']]);
                    /** @var RoomAction $failureRoomAction */
                    $failureRoomAction = $this->roomActionRepository->findOneBy(['name' => $chanceAction['failureRoomActionTitle']]);

                    if ($successRoomAction !== null && $failureRoomAction !== null) {
                        /** @var ChanceAction $chanceAction */
                        $chanceAction = $this->chanceActionFactory->createNewWithBasic(
                            $chanceAction['chance'],
                            $newChoice,
                            $successRoomAction,
                            $failureRoomAction
                        );

                        $this->chanceActionRepository->add($chanceAction);
                    } else {
                        throw new ParameterNotFoundException('Success or Failure RoomAction not found');
                    }
                }
            }
        }
    }
}
