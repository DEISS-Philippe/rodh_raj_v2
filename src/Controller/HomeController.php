<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Repository\RoomActionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    public function displayAction(RoomActionRepository $roomActionRepository)
    {
        /** @var RoomAction $firstRoomAction */
        $firstRoomAction = $roomActionRepository->findOneBy(['code' => 'entree_du_donjon_1']);

        return $this->render('Core/home.html.twig', ['firstRoomId' => $firstRoomAction->getId()]);
    }
}
