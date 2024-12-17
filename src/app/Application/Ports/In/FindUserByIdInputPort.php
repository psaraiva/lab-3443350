<?php

namespace App\Application\Ports\In;

use App\Application\Core\Domain\User;

interface FindUserByIdInputPort
{
    public function find(string $id): User;
}
