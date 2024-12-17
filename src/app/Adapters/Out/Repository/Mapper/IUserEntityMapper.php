<?php

namespace App\Adapters\Out\Repository\Mapper;

use App\Application\Core\Domain\User;
use App\Adapters\Out\Repository\Entity\IUserEntity;

interface IUserEntityMapper
{
    public function toUserEntity(User $user): IUserEntity;
    public function toUser(IUserEntity $userEntity): User;
}
