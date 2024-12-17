<?php

namespace App\Adapters\Out\Repository;

use App\Adapters\Out\Repository\Entity\IUserEntity;

interface IUserRepository
{
    public function query(): array;
    public function delete(string $id): void;
    public function count(): int;
    public function findById(string $id): IUserEntity;
    public function update(IUserEntity $userEntity): void;
    public function insert(IUserEntity $userEntity): void;
}
