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
        //TODO faire perdre la vie au joueur
        //TODO display salle du boss à la 8e salle
        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $roomActionRepository->find($id);
        $currentRoute = $request->attributes->get('_route');
        $currentChoices = $currentRoomAction->getChoices();

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $user->setCurrentRoomAction($currentRoomAction);
        $userRepository->add($user);

        $dispatcher = new EventDispatcher();

        $donjonRoomEvent = new DonjonControllerEvent($currentRoomAction, $user);
        $dispatcher->addListener(DonjonControllerEvent::PRE_DISPLAY_DONJON, $donjonRoomEvent);

        if ($currentRoomAction->getLooseLife() !== null) {
          $life = $user->getLife() - $currentRoomAction->getLooseLife();
          $user->setLife($life);
        }
        if ($user->getLife() === User::LIFE_EMPTY) {
            return $this->redirectToRoute('donjon_you_died');
        }

        // Gestion de navigation si approche salle du boss
        if ($currentRoomAction->isStartRoomAction() === true) {
            if (!empty($request->request->get('addToRoomNumbers')) && $request->request->get('addToRoomNumbers') !== null) {
                $roomNumber = $userRepository->addOneToRoomNumber($user);

                if ($roomNumber >= 7) {
                    /** @var RoomAction $bossRoom */
                    $bossRoom = $roomActionRepository->findOneBy(['code' => 'salle_du_boss_1']);
                    $bossId = $bossRoom->getId();

//                return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $bossId]);
                }
            }
        }

        //Adapte les choice
        $resultChoiceArray = [];
        $itemChoiceArray = [];
        /** @var RoomAction\Choice $choice */
        foreach ($currentChoices as $choice) {
            if (!empty($choice->getChanceAction()) && !empty($choice->getChanceAction()->getChance())) {
                //Si une chance est associée à la réussite de l'action
                /** @var RoomAction\ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $successChance = $chanceAction->getChance();

                //test si réussi
                if ($successChance < rand(0, 10)) {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getFailRoomAction(), 'text' => $choice->getText()];
                } else {
                    $resultChoiceArray[] = ['resultRoomAction' => $chanceAction->getSuccessRoomAction(), 'text' => $choice->getText()];
                }

                //test si le joueur à des items liés aux choice si oui, display le choice
                if (!empty($choice->getItemAction()) && $user->getItems()->contains($choice->getItemAction()->getItem()) && $choice->getItemAction()->isAction() === false) {
                    $itemChoiceArray[] = ['hasItem' => true, 'resultRoomAction' => $choice->getTargetRoomAction(), 'text' => $choice->getText()];
                }
                //Donne au joueur un item
                elseif (!empty($choice->getItemAction()) && $user->getItems()->contains($choice->getItemAction()->getItem()) && $choice->getItemAction()->isAction() === true) {
                    $user->addItem($choice->getItemAction()->getItem());
                    $userRepository->add($user);
                }
            }
        }
        $blackListedRoomActions = $user->getBlackListedRooms();

        //Génère la possible RoomActions à venir
        $availableNextRoomActions = $roomActionRepository->findBy(['isStartRoomAction' => true]);
        $availableNextRoomActions = new ArrayCollection($availableNextRoomActions);
        /** @var RoomAction $roomAction */
        foreach ($availableNextRoomActions as $roomAction) {
            if($roomAction->getCode() === 'entree_du_donjon_1' || $roomAction->getCode() === 'salle_du_boss_1') {
                $availableNextRoomActions->removeElement($roomAction);
            }
            foreach ($blackListedRoomActions as $blackRoomAction)
            if ($roomAction->getId() === $blackRoomAction->getId()){
                $availableNextRoomActions->removeElement($roomAction);
            }
        }
        $availableNextRoomActions = $availableNextRoomActions->toArray();

        $availableNextRoomActions = array_values($availableNextRoomActions);

        $rand = rand(0, (sizeof($availableNextRoomActions) - 1));
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
