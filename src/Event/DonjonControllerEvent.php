<?php

declare(strict_types = 1);

namespace App\Event;

use App\Entity\RoomAction;
use App\Entity\User;
use Symfony\Component\EventDispatcher\Event;

class DonjonControllerEvent extends Event
{
    const PRE_DISPLAY_DONJON = 'app.donjon_room.pre_display';

    protected $subject;
    protected $user;

    public function __construct(RoomAction $subject, User $user, array $arguments = [])
    {
        $this->subject = $subject;
        $this->user = $user;
    }

    /**
     * @return RoomAction
     */
    public function getSubject(): RoomAction
    {
        return $this->subject;
    }

    /**
     * @param RoomAction $subject
     */
    public function setSubject(RoomAction $subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

}
