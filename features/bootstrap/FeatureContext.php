<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }
    /**                                                    
     * @Given A user roomNumber equal to :arg1
     */
    public function aUserRoomnumberEqualTo($arg1)
    {
        throw new PendingException();
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
