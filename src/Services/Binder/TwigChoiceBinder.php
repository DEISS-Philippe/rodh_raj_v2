<?php

namespace App\Services\Binder;

use App\Entity\RoomAction\ChanceAction;
use App\Entity\RoomAction\Choice;
use App\Entity\User;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;

class TwigChoiceBinder
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function bindChoices(Collection $currentChoices, User $user): array
    {
        //Adapte les choice pour un rendu twig
        $resultChoiceArray = [];
        $itemChoiceArray = [];
        /** @var Choice $choice */
        foreach ($currentChoices as $choice) {
            dump($choice);
            //Si une chance est associée à la réussite de l'action
            if (!empty($choice->getChanceAction()) && !empty($choice->getChanceAction()->getChance())) {
                /** @var ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $successChance = $chanceAction->getChance();

                //test si action réussie
                if ($successChance >= rand(0, 10)) {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getFailRoomAction(), 'text' => $choice->getText()];
                } else {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getSuccessRoomAction(), 'text' => $choice->getText()];
                }

                //test si le joueur à des items liés aux choice si oui, display le choice
                if (!empty($choice->getItemAction())
                    && $user->getItems()->contains($choice->getItemAction()->getItem())
                    && $choice->getItemAction()->isAction() === false) {
                    $itemChoiceArray[] = ['hasItem' => true, 'resultRoomAction' => $choice->getTargetRoomAction(), 'text' => $choice->getText()];
                }

                //Donne au joueur un item
                elseif (!empty($choice->getItemAction())
                    && $user->getItems()->contains($choice->getItemAction()->getItem())
                    && $choice->getItemAction()->isAction() === true) {
                    $user->addItem($choice->getItemAction()->getItem());
                    $this->userRepository->add($user);
                }
            }
        }

        return [$resultChoiceArray, $itemChoiceArray];
    }
}
