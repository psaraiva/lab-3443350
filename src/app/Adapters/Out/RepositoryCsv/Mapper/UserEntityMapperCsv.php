<?php

namespace App\Adapters\Out\RepositoryCsv\Mapper;

use App\Application\Core\Domain\User;
use App\Adapters\Out\Repository\Entity\IUserEntity;
use App\Adapters\Out\Repository\Mapper\IUserEntityMapper;
use App\Adapters\Out\RepositoryCsv\Entity\UserEntityCsv;

class UserEntityMapperCsv implements IUserEntityMapper
{
    public function toUserEntity(User $user): IUserEntity
    {
        $userEntity = new UserEntityCsv();
        if ($user->getId()) {
            $userEntity->setId($user->getId());
        }

        if ($user->getName()) {
            $userEntity->setName($user->getName());
        }

        if ($user->getCpf()) {
            $userEntity->setCpf($user->getCpf());
        }

        if ($user->getIsValidCpf()) {
            $userEntity->setIsValidCpf($user->getIsValidCpf());
        }

        return $userEntity;
    }

    public function toUser(IUserEntity $userEntity): User
    {
        $user = new User();
        if ($userEntity->getId()) {
            $user->setId($userEntity->getId());
        }

        if ($userEntity->getName()) {
            $user->setName($userEntity->getName());
        }

        if ($userEntity->getCpf()) {
            $user->setCpf($userEntity->getCpf());
        }

        return $user;
    }
}
