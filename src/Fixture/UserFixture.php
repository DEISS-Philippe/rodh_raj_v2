<?php

namespace App\Fixture;

use App\Entity\User;
use App\Repository\RoomAction\BinderRepository;
use App\Repository\UserRepository;
use App\Services\Generator\BinderGenerator;
use Sylius\Bundle\FixturesBundle\Fixture\AbstractFixture;
use Sylius\Bundle\FixturesBundle\Fixture\FixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends AbstractFixture implements FixtureInterface
{
    private $encoder;
    private $userRepository;
    private $binderGenerator;

    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository,
                                BinderGenerator $binderGenerator)
    {
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
        $this->binderGenerator = $binderGenerator;
    }

    public function load(array $options): void
    {
        $user = new User();
        $user->setName('admin');
        $user->setPassword($this->encoder->encodePassword($user, 'p@ssw0rd'));
        $user->setLife(3);
        $user->setRoomNumber(1);

        $this->userRepository->add($user);
        $this->binderGenerator->generateBindingForMandatoryRoom($user);

        $userBehat = new User();
        $userBehat->setName('behatTestUser');
        $userBehat->setPassword($this->encoder->encodePassword($user, 'behatTestUser1'));
        $userBehat->setLife(3);
        $userBehat->setRoomNumber(1);

        $this->userRepository->add($userBehat);
        $this->binderGenerator->generateBindingForMandatoryRoom($userBehat);
    }

    public function getName():string
    {
        return 'user';
    }
}
