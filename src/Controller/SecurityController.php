<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $lastUserName = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render('Security/login.html.twig', [
            'last_username' => $lastUserName,
            'error' => $error,
        ]);
    }

    public function logout()
    {

    }
}