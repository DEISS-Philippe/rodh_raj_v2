<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Yaml\Yaml;

class HomeController extends AbstractController
{
    public function displayAction()
    {
        //TODO mettre ça dans les fixtures
        $databaseSenderService = $this->container->get('app.service.sender.room');
        $roomsArray = Yaml::parseFile('C:\xampp\htdocs\rodh_raj\private\rooms_data.yaml');

        $databaseSenderService->RoomArraySender($roomsArray);

        return $this->render('Core/home.html.twig');
    }
}
