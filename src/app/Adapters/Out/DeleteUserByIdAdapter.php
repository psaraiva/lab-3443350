<?php

namespace App\Adapters\Out;

use App\Adapters\Out\Repository\IUserRepository;
use App\Application\Ports\Out\DeleteUserByIdOutputPort;

class DeleteUserByIdAdapter implements DeleteUserByIdOutputPort
{
    private IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function delete(string $id): void
    {
        $this->userRepository->delete($id);
    }
}
