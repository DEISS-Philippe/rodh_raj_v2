<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Event\DonjonControllerEvent;
use App\Repository\RoomActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonController extends AbstractController
{
    public function displayRoomAction(
        int $id, RoomActionRepository $roomActionRepository, TokenStorageInterface $tokenStorage
    )
    {
        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $roomActionRepository->find($id);
        $currentChoices = $currentRoomAction->getChoices();

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $dispatcher = new EventDispatcher();

        $donjonRoomEvent = new DonjonControllerEvent($currentRoomAction, $user);
        $dispatcher->addListener(DonjonControllerEvent::PRE_DISPLAY_DONJON, $donjonRoomEvent);

        if ($user->getLife() === User::LIFE_EMPTY) {
            return $this->redirectToRoute('donjon_you_died');
        }

        //Adapte les choice
        $resultChoiceArray = [];
        $itemChoice = [];
        /** @var RoomAction\Choice $choice */
        foreach ($currentChoices as $choice) {
            if (!empty($choice->getChanceAction()->getChance())) {
                //Si une chance est associée à la réussite de l'action
                /** @var RoomAction\ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $failureChance = $chanceAction->getChance();

                //test si réussi
                if ($failureChance <= rand(0, 10)) {
                    $resultChoiceArray[] = ['parentChoice' => $choice->getId(), 'resultRoomAction' => $chanceAction->getSuccessRoomAction()];
                } else {
                    $resultChoiceArray[] = ['parentChoice' => $choice->getId(), 'resultRoomAction' => $chanceAction->getFailRoomAction()];
                }

                //test si le joueur à des items liés aux choice
                if ($user->getItems()->contains($choice->getItemAction()->getItem())) {
                    $itemChoice[] = ['parentChoice' => $choice->getId(), 'hasItem' => true];
                } else {
                    $itemChoice[] = ['parentChoice' => $choice->getId(), 'hasItem' => false];
                }
            }
        }

        dump($currentRoomAction);

        return $this->render('Core/donjon.html.twig', [
            'roomAction' => $currentRoomAction,
            'resultChoiceArray' => $resultChoiceArray,
            'itemChoice' => $itemChoice,
        ]);
    }

    public function displayTestRoomAction()
    {
        return $this->render('Core/donjon.html.twig');
    }
}