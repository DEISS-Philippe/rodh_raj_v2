<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ForbiddenController extends AbstractController
{
    public function displayAction()
    {
        return $this->render('Core/home.html.twig');
    }
}
