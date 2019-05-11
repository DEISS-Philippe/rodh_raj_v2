<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use App\Entity\RoomAction;
use App\Entity\User;
use Sylius\Component\Resource\Model\ResourceInterface;

class Binder implements ResourceInterface
{
    /** @var int|null */
    private $id;
    /** @var User|null */
    private $user;
    /** @var RoomAction|null */
    private $roomAction;
    /** @var string|null */
    private $binderToken;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return RoomAction|null
     */
    public function getRoomAction(): ?RoomAction
    {
        return $this->roomAction;
    }

    /**
     * @param RoomAction|null $roomAction
     */
    public function setRoomAction(?RoomAction $roomAction): void
    {
        $this->roomAction = $roomAction;
    }

    /**
     * @return string|null
     */
    public function getBinderToken(): ?string
    {
        return $this->binderToken;
    }

    /**
     * @param string|null $binderToken
     */
    public function setBinderToken(?string $binderToken): void
    {
        $this->binderToken = $binderToken;
    }
}