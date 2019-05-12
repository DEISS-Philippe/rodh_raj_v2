<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Repository\RoomAction\BinderRepository;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use App\Services\Binder\TwigChoiceBinder;
use App\Services\Generator\BinderGenerator;
use App\Services\Generator\NextRoomGenerator;
use Doctrine\Common\Collections\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonVanillaController extends AbstractController
{
    public function ProcessAction(string $id, TokenStorageInterface $tokenStorage,
                                  UserRepository $userRepository, BinderRepository $binderRepository)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        $currentUserLife = $user->getLife();

        /** @var RoomAction\Binder $binder */
        $binder = $binderRepository->findOneBy(['binderToken' => $id, 'user' => $user]);

        /** @var RoomAction $roomActionToCome */
        $roomActionToCome = $binder->getRoomAction();

        $lifeToLoose = $roomActionToCome->getLooseLife();
        if ($lifeToLoose !== null) {
            $user->setLife($currentUserLife - $lifeToLoose);

            if ($user->getLife() <= User::LIFE_EMPTY) {
                return $this->redirectToRoute('donjon_you_died');
            }
        }

        $itemToAdd = $roomActionToCome->getAddItem();
        if ($itemToAdd !== null) {
            if (!$user->getItems()->contains($itemToAdd)) {
                $user->addItem($itemToAdd);
            }
        }


        $userRepository->add($user);

        return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $id]);
    }

    public function displayRoomAction(Request $request, string $id,
                                      TokenStorageInterface $tokenStorage, UserRepository $userRepository,
                                      TwigChoiceBinder $twigBinder, BinderRepository $binderRepository
    )
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        /** @var RoomAction\Binder $binder */
        $binder = $binderRepository->findOneBy(['binderToken' => $id, 'user' => $user]);

        $currentRoomAction = $binder->getRoomAction();
        $currentRoute = $request->attributes->get('_route');
        /** @var RoomAction\Choice[]|Collection $currentChoices */
        $currentChoices = $currentRoomAction->getChoices();

        $user->setCurrentRoomAction($currentRoomAction);
        $userRepository->add($user);

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

    public function startGameAction(TokenStorageInterface $tokenStorage,
                                    UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                    BinderGenerator $binderGenerator, BinderRepository $binderRepository)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        // Enlève les anciens bindings
        $binderRepository->removeFormerBinderForUser($user);
        $binderGenerator->generateBindingForMandatoryRoom($user);

        /** @var RoomAction $firstRoomAction */
        $firstRoomAction = $roomActionRepository->findEntranceRoomAction();
        $userRepository->resetUserGameData($user, $firstRoomAction);

        /** @var RoomAction\Binder $binder */
        $binder = $binderRepository->findOneBy(['roomAction' => $firstRoomAction, 'user' => $user]);

        return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $binder->getBinderToken()]);
    }

    public function buildNextRoomAction(TokenStorageInterface $tokenStorage,
                                        UserRepository $userRepository, RoomActionRepository $roomActionRepository,
                                        NextRoomGenerator $nextRoomGenerator, BinderGenerator $binderGenerator,
                                        BinderRepository $binderRepository)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $roomNumber = $userRepository->addOneToRoomNumber($user);
        if ($roomNumber > 7) {
            /** @var RoomAction $bossRoom */
            $bossRoomAction = $roomActionRepository->findBossRoomAction();
            /** @var RoomAction\Binder $bossBinder */
            $bossBinder = $binderRepository->findOneBy(['roomAction' => $bossRoomAction, 'user' => $user]);

            return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $bossBinder->getBinderToken()]);
        }
        $nextRoomAction = $nextRoomGenerator->generateNextRoom($user);

        // Enlève les anciens bindings
        $binderRepository->removeFormerBinderForUser($user);
        //Génère les bindings pour les routes possibles
        $binderGenerator->generateBindings($nextRoomAction);

        /** @var RoomAction\Binder $binder */
        $binder = $binderRepository->findOneBy(['roomAction' => $nextRoomAction, 'user' => $user]);

        $blackList = $user->getBlackListedRooms();
        $blackList->add($nextRoomAction);
        $userRepository->add($user);

        return $this->redirectToRoute('donjon_vanilla_display_room', ['id' => $binder->getBinderToken()]);
    }
}
