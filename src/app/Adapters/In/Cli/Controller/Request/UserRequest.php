<?php

namespace App\Adapters\In\Cli\Controller\Request;

class UserRequest
{
    private string $name;
    private string $cpf;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        if (strlen($name) < 3) {
            throw new \InvalidArgumentException('Name must have at least 3 characters');
        }

        $this->name = $name;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        if (strlen($cpf) < 11) {
            throw new \InvalidArgumentException('CPF must have at least 11 characters');
        }

        $this->cpf = $cpf;
    }
}
