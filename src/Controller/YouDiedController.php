<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class YouDiedController extends AbstractController
{
    public function displayAction(
        RoomActionRepository $roomActionRepository, TokenStorageInterface $tokenStorage,
        UserRepository $userRepository
    )
    {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        /** @var RoomAction $startRoomAction */
        $startRoomAction = $roomActionRepository->findOneBy(['code' => 'entree_du_donjon_1']);

        //reset les données de jeu
        $userRepository->resetUserGameData($user, $startRoomAction);

        return $this->render('Core/you_died.html.twig');
    }
}