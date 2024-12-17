<?php

namespace App\Application\Core\UseCase;

use App\Application\Core\Domain\User;
use App\Application\Ports\Out\UpdateUserOutputPort;
use App\Application\Ports\In\UpdateUserInputPort;

class UpdateUserUseCase implements UpdateUserInputPort
{
    private UpdateUserOutputPort $updateUserOutputPort;

    public function __construct(UpdateUserOutputPort $updateUserOutputPort)
    {
        $this->updateUserOutputPort = $updateUserOutputPort;
    }

    public function update(User $user): void
    {
        $this->updateUserOutputPort->update($user);
    }
}
