<?php

namespace App\Application\Ports\Out;

interface DeleteUserByIdOutputPort
{
    public function delete(string $id): void;
}
