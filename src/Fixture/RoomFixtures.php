<?php

namespace App\Fixture;

use App\Entity\RoomAction\Choice;
use App\Factory\ItemFactory;
use App\Factory\RoomAction\ChanceActionFactory;
use App\Factory\RoomAction\ChoiceFactory;
use App\Factory\RoomAction\ItemActionFactory;
use App\Factory\RoomAction\RoomFactory;
use App\Factory\RoomActionFactory;
use App\Repository\ItemRepository;
use App\Repository\RoomAction\ChanceActionRepository;
use App\Repository\RoomAction\ChoiceRepository;
use App\Repository\RoomAction\ItemActionRepository;
use App\Repository\RoomAction\RoomRepository;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;

class RoomFixtures extends AbstractFixture implements FixtureInterface
{
    private $userRepository;
    private $roomActionRepository;
    private $roomActionFactory;
    private $roomFactory;
    private $choiceFactory;
    private $chanceActionFactory;
    private $itemActionFactory;
    private $itemFactory;
    private $roomRepository;
    private $choiceRepository;
    private $chanceActionRepository;
    private $itemActionRepository;
    private $itemRepository;

    public function __construct(UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                RoomActionFactory $roomActionFactory, RoomFactory $roomFactory,
                                ChoiceFactory $choiceFactory, ChanceActionFactory $chanceActionFactory,
                                ItemActionFactory $itemActionFactory, ItemFactory $itemFactory,
                                RoomRepository $roomRepository, ChoiceRepository $choiceRepository,
                                ChanceActionRepository $chanceActionRepository,
                                ItemActionRepository $itemActionRepository, ItemRepository $itemRepository)
    {
        $this->userRepository = $userRepository;
        $this->roomActionRepository = $roomActionRepository;
        $this->roomActionFactory = $roomActionFactory;
        $this->roomFactory = $roomFactory;
        $this->choiceFactory = $choiceFactory;
        $this->chanceActionFactory = $chanceActionFactory;
        $this->itemActionFactory = $itemActionFactory;
        $this->itemFactory = $itemFactory;
        $this->roomRepository = $roomRepository;
        $this->choiceRepository = $choiceRepository;
        $this->chanceActionRepository = $chanceActionRepository;
        $this->itemActionRepository = $itemActionRepository;
        $this->itemRepository = $itemRepository;
    }

    public function load(array $options): void
    {
//        $room = $this->roomFactory->createNewWithName('Entrée du Donjon');
//        $this->roomRepository->add($room);
//
//        $roomAction = $this->roomActionFactory->createNewWithBasicValues(
//            'Salutations aventurier ! MOI, le maître du jeu vous accompagnerai durant votre folle aventure.
//
//Certaines épreuves marquent un virage dans une existence. D’autres ne sont que ramassis de futilités posées sur le chemin de la vie tel un étron fumant attendant patiemment le pied du passant distrait.
//Un donjon est l’épreuve ultime pour tout aventurier recherchant gloire, argent ou corps biens proportionnés. De biens nombreuses raisons de s’aventurer dans un endroit ou le danger est à chaque tournant.
//
//Oserez-vous défier le légendaire mage mauve en pénétrant dans le donjon ?
//');
//
//        $this->roomActionRepository->add($roomAction);
//
//        /** @var Choice $choice */
//        $choice = $this->choiceFactory->createNewWithBasicValues('Se rendre au Donjon', $roomAction);
//        $this->choiceRepository->add($choice);
    }

    public function getName():string
    {
        return 'room';
    }
}
