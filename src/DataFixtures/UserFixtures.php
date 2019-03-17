<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    #TODO Add factoryInterface and factories

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setName('admin');
        $user->setPassword($this->encoder->encodePassword($user, 'p@ssw0rd'));
        $user->setLife(3);
        $user->setRoomNumber(1);
        $manager->persist($user);

        $manager->flush();
    }
}
