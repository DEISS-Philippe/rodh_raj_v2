<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Repository\RoomAction\BinderRepository;
use App\Repository\RoomActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class HomeController extends AbstractController
{
    public function displayAction(RoomActionRepository $roomActionRepository, BinderRepository $binderRepository,
                                  TokenStorageInterface $tokenStorage)
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        /** @var RoomAction $firstRoomAction */
        $firstRoomAction = $roomActionRepository->findEntranceRoomAction();

        /** @var RoomAction\Binder $binder */
        $binder = $binderRepository->findOneBy(['roomAction' => $firstRoomAction, 'user' => $user]);

        return $this->render('Core/home.html.twig', ['firstRoomBinderToken' => $binder->getBinderToken()]);
    }
}
