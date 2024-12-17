<?php

namespace App\Application\Ports\Out;

use App\Application\Core\Domain\User;

interface InsertUserOutputPort
{
    public function insert(User $user): void;
}
