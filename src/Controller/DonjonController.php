<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Event\DonjonControllerEvent;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonController extends AbstractController
{
    public function displayRoomAction(Request $request, int $id, RoomActionRepository $roomActionRepository,
                                      TokenStorageInterface $tokenStorage, UserRepository $userRepository
    )
    {
        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $roomActionRepository->find($id);
        $currentRoute = $request->attributes->get('_route');
        $currentChoices = $currentRoomAction->getChoices();

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $dispatcher = new EventDispatcher();

        $donjonRoomEvent = new DonjonControllerEvent($currentRoomAction, $user);
        $dispatcher->addListener(DonjonControllerEvent::PRE_DISPLAY_DONJON, $donjonRoomEvent);

        if ($user->getLife() === User::LIFE_EMPTY) {
            return $this->redirectToRoute('donjon_you_died');
        }

        // Gestion de navigation si approche salle du boss
        if ($currentRoomAction->isEndOfRoom() === true) {
            $roomNumber = $userRepository->addOneToRoomNumber($user);
            if ($roomNumber >= 7) {

                /** @var RoomAction $bossRoom */
                $bossRoom = $roomActionRepository->findOneBy(['name' => 'Salle du Boss']);

                if ($currentRoute === 'donjon_vanilla_display_room') {
                    return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $bossRoom->getId()]);
                }
                elseif($currentRoute === 'donjon_custom_display_room') {
                    return $this->redirectToRoute('donjon_custom_display_room', ['id' => $bossRoom->getId()]);
                }
                else {
                    throw new BadRequestHttpException('Vous vous êtes perdu dans le néant ?');
                }
            }
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
        $blackListedRoomActions = $user->getBlackListedRooms();

        //Génère la possible RoomActions à venir
        $availableNextRoomActions = $roomActionRepository->findBy(['isStartRoomAction' => true]);
        $availableNextRoomActions = new ArrayCollection($availableNextRoomActions);
        /** @var RoomAction $roomAction */
        foreach ($availableNextRoomActions as $roomAction) {
            foreach ($blackListedRoomActions as $blackRoomAction)
            if ($roomAction->getId() === $blackRoomAction->getId()){
                $availableNextRoomActions->removeElement($roomAction);
            }
        }
        $availableNextRoomActions = $availableNextRoomActions->toArray();

        $rand = rand(0, sizeof($availableNextRoomActions));
        /** @var RoomAction $nextRoomAction */
        $nextRoomAction = $availableNextRoomActions[$rand];
        $nextRoomActionId = $nextRoomAction->getId();

        if ($currentRoute === 'donjon_vanilla_display_room') {
            return $this->render('Core/donjon_vanilla.html.twig', [
                'roomAction' => $currentRoomAction,
                'resultChoiceArray' => $resultChoiceArray,
                'itemChoiceArray' => $itemChoiceArray,
                'nextRoomActionId' => $nextRoomActionId,
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