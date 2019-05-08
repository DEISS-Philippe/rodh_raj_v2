<?php

use App\Repository\UserRepository;
use Behat\Behat\Context\Context;

/**
 * Defines application features from the specific context.
 */
class DonjonContext implements Context
{
    /** @var \App\Entity\User|null  */
    public $user;
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct(UserRepository $userRepository)
    {
        $user = $userRepository->findOneBy(['name' => 'behatTestuser']);
        $this->user = $user;
    }
    /**
     * @Given A user roomNumber equal to :arg1
     */
    public function aUserRoomnumberEqualTo($roomNumber)
    {
        $user = $this->user;
        if ($user->getRoomNumber() === $roomNumber) {
            throw new Exception('RoomNumbers does not match');
        }
    }

    /**
     * @Given the currentRoomCode is equal to :arg1
     */
    public function theCurrentroomcodeIsEqualTo($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When the user click the link to go to the next Room
     */
    public function theUserClickTheLinkToGoToTheNextRoom()
    {
        throw new PendingException();
    }

    /**
     * @Then the next Room has startRoomAction
     */
    public function theNextRoomHasStartroomaction()
    {
        throw new PendingException();
    }

    /**
     * @Then the user roomNumber is equal to :arg1
     */
    public function theUserRoomnumberIsEqualTo($arg1)
    {
        throw new PendingException();
    }
}
