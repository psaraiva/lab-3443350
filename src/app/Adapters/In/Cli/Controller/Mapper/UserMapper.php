<?php

namespace App\Adapters\In\Cli\Controller\Mapper;

use App\Adapters\In\Cli\Controller\Response\UserResponse;
use App\Application\Core\Domain\User;

class UserMapper
{
    public function toUser(string $userJson): User
    {
        $user = new User();
        $data = json_decode($userJson, true, 2, JSON_THROW_ON_ERROR);

        if (isset($data['id'])) {
            $user->setId($data['id']);
        }

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['cpf'])) {
            $user->setCpf($data['cpf']);
        }

        return $user;
    }

    public function toUserResponse(User $user): UserResponse
    {
        $userResponse = new UserResponse();
        $userResponse->setName($user->getName());
        $userResponse->setCpf($user->getCpf());
        return $userResponse;
    }
}
