<?php
declare(strict_types=1);

namespace App\Entity\RoomAction;

use Sylius\Component\Resource\Model\ResourceInterface;

class ChanceAction implements ResourceInterface
{
    /** @var int */
    private $id;
    /** @var Choice */
    private $parentChoice = null;
    /** @var int */
    private $chance = 0;
//    /** @var Choice */
//    private $successChoice = null;
//    /** @var Choice */
//    private $failChoice = null;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Choice
     */
    public function getParentChoice(): Choice
    {
        return $this->parentChoice;
    }

    /**
     * @param Choice $parentChoice
     */
    public function setParentChoice(Choice $parentChoice): void
    {
        $this->parentChoice = $parentChoice;
    }

    /**
     * @return int
     */
    public function getChances(): int
    {
        return $this->chance;
    }

    /**
     * @param int $chance
     */
    public function setChances(int $chance): void
    {
        $this->chance = $chance;
    }
//
//    /**
//     * @return Choice
//     */
//    public function getSuccessChoice(): Choice
//    {
//        return $this->successChoice;
//    }
//
//    /**
//     * @param Choice $successChoice
//     */
//    public function setSuccessChoice(Choice $successChoice): void
//    {
//        $this->successChoice = $successChoice;
//    }
//
//    /**
//     * @return Choice
//     */
//    public function getFailChoice(): Choice
//    {
//        return $this->failChoice;
//    }
//
//    /**
//     * @param Choice $failChoice
//     */
//    public function setFailChoice(Choice $failChoice): void
//    {
//        $this->failChoice = $failChoice;
//    }

}