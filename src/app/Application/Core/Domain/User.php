<?php

namespace App\Application\Core\Domain;

class User
{
    private $id = '';
    private $name = '';
    private $cpf = '';
    private $isValidCpf = false;

    public function __construct(
        string $id='',
        string $name='',
        string $cpf='',
        bool $isValidCpf=false)
    {
        $this->id = $id;
        $this->name = $name;
        $this->cpf = $cpf;
        $this->isValidCpf = $isValidCpf;
    }

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
}
