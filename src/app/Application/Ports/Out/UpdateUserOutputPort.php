<?php

namespace App\Application\Ports\Out;

use App\Application\Core\Domain\User;

interface UpdateUserOutputPort
{
    public function update(User $user): void;
}
