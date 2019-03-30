<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Yaml\Yaml;

class HomeController extends AbstractController
{
    public function displayAction()
    {
        // TODO au passage sur un vrai serveur changer le lien
        $value = Yaml::parseFile('C:\xampp\htdocs\rodh_raj\private\data.yaml');

        dump($value);

        return $this->render('Core/home.html.twig');
    }
}
