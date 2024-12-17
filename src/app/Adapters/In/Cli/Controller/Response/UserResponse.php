<?php

namespace App\Adapters\In\Cli\Controller\Response;

class UserResponse
{
    private string $name;
    private string $cpf;
    private bool $isCpfValid;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getCpf(): string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): void
    {
        $this->cpf = $cpf;
    }

    public function getIsCpfValid(): bool
    {
        return $this->isCpfValid;
    }

    public function setIsCpfValid(bool $isCpfValid): void
    {
        $this->isCpfValid = $isCpfValid;
    }

    public function toJson(): string
    {
        return json_encode([
            'name' => $this->name,
            'cpf' => $this->cpf,
        ]);
    }
}
