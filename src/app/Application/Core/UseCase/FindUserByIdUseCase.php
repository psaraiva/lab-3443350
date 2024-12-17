<?php

namespace App\Application\Core\UseCase;

use App\Application\Core\Domain\User;
use App\Application\Ports\In\FindUserByIdInputPort;
use App\Application\Ports\Out\FindUserByIdOutputPort;

class FindUserByIdUseCase implements FindUserByIdInputPort
{
    private FindUserByIdOutputPort $findUserByIdOutputPort;

    public function __construct(FindUserByIdOutputPort $findUserByIdOutputPort)
    {
        $this->findUserByIdOutputPort = $findUserByIdOutputPort;
    }

    public function find (string $id): User
    {
        $user = $this->findUserByIdOutputPort->find($id);
        if ($user->getId() == null) {
            throw new \Exception("User not found");
        }

        return $user;
    }
}
