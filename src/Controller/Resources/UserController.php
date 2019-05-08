<?php

namespace App\Controller\Resources;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController as BaseResourceController;

class ResourceController extends BaseResourceController
{
    public function test(){
        phpinfo();
    }
}