<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Event\DonjonControllerEvent;
use App\Repository\RoomActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonController extends AbstractController
{
    public function displayRoomAction(
        Request $request, int $id, RoomActionRepository $roomActionRepository, TokenStorageInterface $tokenStorage
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
        $itemChoiceArray = [];
        /** @var RoomAction\Choice $choice */
        foreach ($currentChoices as $choice) {
            if (!empty($choice->getChanceAction()->getChance())) {
                //Si une chance est associée à la réussite de l'action
                /** @var RoomAction\ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $failureChance = $chanceAction->getChance();

                //test si réussi
                if ($failureChance <= rand(0, 10)) {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getFailRoomAction(), 'text' => $choice->getText()];
                } else {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getSuccessRoomAction(), 'text' => $choice->getText()];
                }

                //test si le joueur à des items liés aux choice
                if ($user->getItems()->contains($choice->getItemAction()->getItem())) {
                    $itemChoiceArray[] = ['hasItem' => true, 'resultRoomAction' => $choice->getTargetRoomAction(), 'text' => $choice->getText()];
                } else {
                    $itemChoiceArray[] = ['hasItem' => false, 'text' => $choice->getText()];
                }
            }
        }

        $currentRoute = $request->attributes->get('_route');

        if ($currentRoute === 'donjon_vanilla_display_room') {
            return $this->render('Core/donjon_vanilla.html.twig', [
                'roomAction' => $currentRoomAction,
                'resultChoiceArray' => $resultChoiceArray,
                'itemChoiceArray' => $itemChoiceArray,
            ]);
        }
        elseif($currentRoute === 'donjon_custom_display_room') {
            return $this->render('Core/donjon_custom.html.twig', [
                'roomAction' => $currentRoomAction,
                'resultChoiceArray' => $resultChoiceArray,
                'itemChoiceArray' => $itemChoiceArray,
            ]);
        }
        else {
            throw new BadRequestHttpException('Vous vous êtes perdu dans le néant ?');
        }
    }

    public function displayTestRoomAction()
    {
        return $this->render('Core/donjon_test.html.twig');
    }
}