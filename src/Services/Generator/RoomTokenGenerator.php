<?php

declare(strict_types=1);

namespace App\Services\Generator;

use Sylius\Component\Resource\Repository\RepositoryInterface;

class RoomTokenGenerator
{
    private $binderRepository;

    public function __construct(RepositoryInterface $binderRepository)
    {
        $this->binderRepository = $binderRepository;
    }

    public function generateRandomToken(): string
    {
        $token = 'room_'.\md5(\random_bytes(8));

        if ($this->binderRepository->findOneBy(['binderToken' => $token]) !== null) {
            $this->generateRandomToken();
        }

        return $token;
    }
}
