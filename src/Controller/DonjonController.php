<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\RoomAction;
use App\Entity\User;
use App\Repository\RoomActionRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class DonjonController extends AbstractController
{
    public function displayRoomAction(
        int $id, RoomActionRepository $roomActionRepository, TokenStorageInterface $tokenStorage
    )
    {
        //TODO passer les tests de début de roomAction en listeners ?

        /** @var RoomAction $currentRoomAction */
        $currentRoomAction = $roomActionRepository->find($id);
        $currentChoices = $currentRoomAction->getChoices();

        /** @var User $user */
        $user = $tokenStorage->getToken()->getUser();

        $lifeToLoose = $currentRoomAction->getLooseLife();
        if ($lifeToLoose !== null) {
            // Si la sale fait perdre de la vie, l'enlève à celle du user
            $currentUserLife = $user->getLife();
            $user->setLife($currentUserLife - $lifeToLoose);

            if ($user->getLife() === User::LIFE_EMPTY) {
                //Le joueur est mort
                return $this->redirectToRoute('donjon_you_died');
            }
        }

        //Adapte les choice
        $resultChoiceArray = [];
        $itemChoice = [];
        /** @var RoomAction\Choice $choice */
        foreach ($currentChoices as $choice) {
            if ($choice->getChanceAction() && $choice->getChanceAction()->getChance() !== null) {
                //Si une chance est associée à la réussite de l'action
                /** @var RoomAction\ChanceAction $chanceAction */
                $chanceAction = $choice->getChanceAction();
                $failureChance = $chanceAction->getChance();

                //test si réussi
                if ($failureChance <= rand(0, 10)) {
                    $resultChoiceArray[] = ['parentChoice' => $choice->getId(), 'resultRoomAction' => $chanceAction->getSuccessRoomAction()];
                } else {
                    $resultChoiceArray[] = ['parentChoice' => $choice->getId(), 'resultRoomAction' => $chanceAction->getFailRoomAction()];
                }

                //test si le joueur à des items liés aux choice
                if ($user->getItems()->contains($choice->getItemAction()->getItem())) {
                    $itemChoice[] = ['parentChoice' => $choice->getId(), 'hasItem' => true];
                } else {
                    $itemChoice[] = ['parentChoice' => $choice->getId(), 'hasItem' => false];
                }
            }
        }

        dump($resource);

        return $this->render('Core/donjon.html.twig', [
            'resource' => $resource,
        ]);
    }

    public function displayTestRoomAction()
    {
        return $this->render('Core/donjon.html.twig');
    }
}