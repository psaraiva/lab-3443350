<?php

namespace App\Adapters\Out;

use App\Application\Ports\Out\InsertUserOutputPort;
use App\Application\Core\Domain\User;
use App\Adapters\Out\Repository\IUserRepository;
use App\Adapters\Out\Repository\Mapper\IUserEntityMapper;

class InsertUserAdapter implements InsertUserOutputPort
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

    public function insert(User $user): void
    {
        $userEntity = $this->userEntityMapper->toUserEntity($user);
        $this->userRepository->insert($userEntity);
    }
}
