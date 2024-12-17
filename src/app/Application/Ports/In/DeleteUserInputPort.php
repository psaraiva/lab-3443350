<?php

namespace App\Application\Ports\In;

interface DeleteUserInputPort
{
    public function delete(string $id): void;
}
