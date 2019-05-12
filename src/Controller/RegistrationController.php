<?php

namespace App\Controller;

use App\Entity\User;
use App\Factory\UserFactory;
use App\Form\Type\User\UserType;
use App\Repository\RoomAction\BinderRepository;
use App\Repository\UserRepository;
use App\Services\Generator\BinderGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    public function register(Request $request, UserPasswordEncoderInterface $encoder, UserFactory $userFactory,
                             UserRepository $userRepository, BinderGenerator $binderGenerator): Response
    {
        /** @var User $user */
        $user = $userFactory->createNewBasicUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $encoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setName($form->get('name')->getData());

            //Ajoute direct les bindings pour salles de base
            $binderGenerator->generateBindingForMandatoryRoom($user);

            $userRepository->add($user);

            return $this->redirectToRoute('login');
        }

        return $this->render('Resources/User/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
