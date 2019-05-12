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
    public function displayAction()
    {
        return $this->render('Core/you_died.html.twig');
    }
}
