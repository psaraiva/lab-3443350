<?php

namespace App\Adapters\Out\RepositoryJson\Entity;

use App\Adapters\Out\Repository\Entity\IUserEntity;

class UserEntityJson implements IUserEntity
{
    private string $id = '';
    private string $name = '';
    private string $cpf = '';
    private bool $isValidCpf = false;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

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

    public function getIsValidCpf(): bool
    {
        return $this->isValidCpf;
    }

    public function setIsValidCpf(bool $isValidCpf): void
    {
        $this->isValidCpf = $isValidCpf;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'cpf' => $this->cpf,
            'isValidCpf' => $this->isValidCpf,
        ];
    }
}
