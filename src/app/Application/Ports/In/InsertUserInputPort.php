<?php

namespace App\Application\Ports\In;

use App\Application\Core\Domain\User;

interface InsertUserInputPort
{
    public function insert(User $user): void;
}