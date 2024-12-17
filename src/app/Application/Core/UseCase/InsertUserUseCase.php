<?php

namespace App\Application\Core\UseCase;

use App\Application\Core\Domain\User;
use App\Application\Ports\Out\InsertUserOutputPort;
use App\Application\Ports\In\InsertUserInputPort;

class InsertUserUseCase implements InsertUserInputPort
{
    private $insertUserOutputPort;

    public function __construct(InsertUserOutputPort $insertUserOutputPort)
    {
        $this->insertUserOutputPort = $insertUserOutputPort;
    }

    public function insert(User $user): void
    {
        $this->insertUserOutputPort->insert($user);
    }
}
