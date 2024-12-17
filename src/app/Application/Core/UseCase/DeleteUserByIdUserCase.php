<?php

namespace App\Application\Core\UseCase;

use App\Application\Ports\Out\DeleteUserByIdOutputPort;
use App\Application\Ports\In\DeleteUserInputPort;

class DeleteUserByIdUserCase implements DeleteUserInputPort
{
    private DeleteUserByIdOutputPort $deleteUserByIdOutputPort;

    public function __construct(DeleteUserByIdOutputPort $deleteUserByIdOutputPort)
    {
        $this->deleteUserByIdOutputPort = $deleteUserByIdOutputPort;
    }

    public function delete(string $id): void
    {
        $this->deleteUserByIdOutputPort->delete($id);
    }
}
