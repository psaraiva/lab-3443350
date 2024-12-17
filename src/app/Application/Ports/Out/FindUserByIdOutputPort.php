<?php

namespace App\Application\Ports\Out;

use App\Application\Core\Domain\User;

interface FindUserByIdOutputPort
{
    public function find(string $id): User;
}
