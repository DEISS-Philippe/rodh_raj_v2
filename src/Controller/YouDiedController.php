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
        $deathNotes = [
            'Heureusement que vous êtes là pour nourrir les rats !',
            'Vous avez combattu vaillamment ! Non je rigole, je vais vous épargner les détails...',
            'Vous pensiez vraiment que l’issue allait être différente ?',
            '*Sarcasme* Ah ben en voilà une surprise !',
            'Tiens ? Vous êtes en êtes de nouveau là ?',
            'Un chimpanzé aurait fait mieux.',
        ];

        return $this->render('Core/you_died.html.twig', ['deathNote' => $deathNotes[ rand(0,(sizeof($deathNotes)-1)) ] ]);
    }
}
