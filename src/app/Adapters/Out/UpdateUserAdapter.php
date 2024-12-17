<?php

namespace App\Adapters\Out;

use App\Adapters\Out\Repository\Mapper\IUserEntityMapper;
use App\Adapters\Out\Repository\IUserRepository;
use App\Application\Core\Domain\User;
use App\Application\Ports\Out\UpdateUserOutputPort;

class UpdateUserAdapter implements UpdateUserOutputPort
{
    private IUserRepository $userRepository;
    private IUserEntityMapper $userEntityMapper;

    public function __construct(
        IUserRepository $iUserRepository,
        IUserEntityMapper $iUserEntityMapper
    ) {
        $this->userRepository = $iUserRepository;
        $this->userEntityMapper = $iUserEntityMapper;
    }

    public function update(User $user): void
    {
        $userEntity = $this->userEntityMapper->toUserEntity($user);
        $this->userRepository->update($userEntity);
    }
}
