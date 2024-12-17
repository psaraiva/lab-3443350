<?php

namespace App\Application\Ports\In;

use App\Application\Core\Domain\User;

interface UpdateUserInputPort
{
    public function update(User $user): void;
}
