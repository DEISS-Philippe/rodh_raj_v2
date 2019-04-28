<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use App\Repository\RoomActionRepository;

class HomeController extends AbstractController
{
    public function displayAction(Request $request, UserRepository $userRepository, TokenStorageInterface $tokenStorage,
    RoomActionRepository $roomActionRepository)
    {
      if (!empty($request->query->get('isBackToMenu')) && $request->query->get('isBackToMenu') !== null) {
        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();
        $firstRoomAction = $roomActionRepository->findOneBy(['code' => 'entree_du_donjon_1']);

        $userRepository->resetUserGameData($user, $firstRoomAction);
      }
        return $this->render('Core/home.html.twig');
    }
}
