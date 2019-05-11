<?php
declare(strict_types=1);

namespace App\Repository\RoomAction;

use App\Entity\User;

class BinderRepository extends \Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository
{
    public function removeFormerBinderForUser(User $user)
    {
        $formerBinders = $this->findBy(['user' => $user]);

        foreach ($formerBinders as $formerBinder) {
            $this->remove($formerBinder);
        }
    }
}