<?php

namespace App\Adapters\Out\Repository\Entity;

interface IUserEntity
{
    public function getId(): string;
    public function setId(string $id): void;
    public function getName(): string;
    public function setName(string $name): void;
    public function getCpf(): string;
    public function setCpf(string $cpf): void;
    public function getIsValidCpf(): bool;
    public function setIsValidCpf(bool $isValidCpf): void;
    public function toArray(): array;
}
