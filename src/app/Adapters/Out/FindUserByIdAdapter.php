<?php

namespace App\Adapters\Out;

use App\Application\Core\Domain\User;
use App\Adapters\Out\Repository\IUserRepository;
use App\Adapters\Out\Repository\Mapper\IUserEntityMapper;
use App\Application\Ports\Out\FindUserByIdOutputPort;

class FindUserByIdAdapter implements FindUserByIdOutputPort
{
    private IUserRepository $userRepository;
    private IUserEntityMapper $userEntityMapper;

    public function __construct(
        IUserRepository $iUserRepository,
        IUserEntityMapper $iUserEntityMapper)
    {
        $this->userRepository = $iUserRepository;
        $this->userEntityMapper = $iUserEntityMapper;
    }

    public function find(string $id): User
    {
        $userEntity = $this->userRepository->findById($id);
        return $this->userEntityMapper->toUser($userEntity);
    }
}
