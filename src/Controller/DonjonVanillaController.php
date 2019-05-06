<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Event\DonjonControllerEvent;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use App\Services\Binder\TwigChoiceBinder;
use App\Services\Generator\NextRoomGenerator;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonVanillaController extends AbstractController
{
    public function endGameAction(TokenStorageInterface $tokenStorage,
                                  UserRepository $userRepository, RoomActionRepository $roomActionRepository)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        /** @var RoomAction $firstRoomAction */
        $firstRoomAction = $roomActionRepository->findOneBy(['code' => 'entree_du_donjon_1']);

        $userRepository->resetUserGameData($user, $firstRoomAction);

        return $this->redirectToRoute('homepage');
    }

    public function buildNextRoomAction(TokenStorageInterface $tokenStorage,
                                  UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                  NextRoomGenerator $nextRoomGenerator)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $roomNumber = $userRepository->addOneToRoomNumber($user);
        if ($roomNumber >= 7) {
            /** @var RoomAction $bossRoom */
            $bossRoom = $roomActionRepository->findOneBy(['code' => 'salle_du_boss_1']);
            $bossId = $bossRoom->getId();

            return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $bossId]);
        }
        $nextRoomActionId = $nextRoomGenerator->generateNextRoomId($user);

        return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $nextRoomActionId]);
    }

    public function displayRoomAction(Request $request, int $id, RoomActionRepository $roomActionRepository,
                                      TokenStorageInterface $tokenStorage, UserRepository $userRepository,
                                      TwigChoiceBinder $twigBinder
    )
    {
        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $roomActionRepository->find($id);
        $currentRoute = $request->attributes->get('_route');
        /** @var RoomAction\Choice[]|Collection $currentChoices */
        $currentChoices = $currentRoomAction->getChoices();

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $user->setCurrentRoomAction($currentRoomAction);
        $userRepository->add($user);

        $dispatcher = new EventDispatcher();

        //Life handling
        $donjonRoomEvent = new DonjonControllerEvent($currentRoomAction, $user);
        $dispatcher->addListener(DonjonControllerEvent::PRE_DISPLAY_DONJON, $donjonRoomEvent);

        if ($user->getLife() === User::LIFE_EMPTY) {
            return $this->redirectToRoute('donjon_you_died');
        }

        [$resultChoiceArray, $itemChoiceArray] = $twigBinder->bindChoices($currentChoices, $user);

        if ($currentRoute === 'donjon_vanilla_display_room') {
            return $this->render('Core/donjon_vanilla.html.twig', [
                'roomAction' => $currentRoomAction,
                'resultChoiceArray' => $resultChoiceArray,
                'itemChoiceArray' => $itemChoiceArray,
            ]);
        }
        else {
            throw new BadRequestHttpException('Vous vous êtes perdu dans le néant ?');
        }
    }
}