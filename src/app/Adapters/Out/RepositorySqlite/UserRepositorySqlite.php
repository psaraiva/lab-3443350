<?php

namespace App\Adapters\Out\RepositorySqlite;

use App\Adapters\Out\Repository\Entity\IUserEntity;
use App\Adapters\Out\Repository\IUserRepository;

use PDO;
use Exception;
use DateTime;
use DateTimeZone;

class UserRepositorySqlite implements IUserRepository
{
    private $pdo = null;
    private $userEntity = null;

    // @todo: migrar lógica de verificação da db para um método
    public function __construct(IUserEntity $userEntity)
    {
        $this->userEntity = $userEntity;
        $this->initPdo();
    }

    private function initPdo(): void
    {
        $pdo = new PDO('sqlite:'. APP_PATH . '/data/sqlite/data.db');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='users'");
        if (! $result->fetch()) {
            throw new Exception("Table 'users' not found.");
        }

        $this->pdo = $pdo;
    }

    /**
     * @return UserEntity[]
     */
    public function query(): array
    {
        return [];
    }

    public function insert(IUserEntity $userEntity): void
    {
        $dateTime = new DateTime('now', new DateTimeZone('UTC'));
        $now = $dateTime->format(DateTime::ATOM);

        $stmt = $this->pdo->prepare("
        INSERT INTO users (
                    nome,
                    cpf,
                    valido,
                    created_at,
                    updated_at)
             VALUES (
                    :nome,
                    :cpf,
                    :valido,
                    :created_at,
                    :updated_at)");

        $isValidCpf = (int) $userEntity->getIsValidCpf();
        $stmt->execute([
            ':nome' => $userEntity->getName(),
            ':cpf' => $userEntity->getCpf(),
            ':valido' => $isValidCpf,
            ':created_at' => $now,
            ':updated_at' => $now,
        ]);

        if ($stmt->rowCount() != 1) {
            throw new Exception("Error inserting user");
        }
    }

    public function update(IUserEntity $userEntity): void
    {
        if ($this->findById($userEntity->getId())->getId() == '') {
            throw new Exception("User not found");
        }

        $dateTime = new DateTime('now', new DateTimeZone('UTC'));
        $now = $dateTime->format(DateTime::ATOM);

        $stmt = $this->pdo->prepare("
        UPDATE users
           SET nome = :nome,
               cpf = :cpf,
               valido = :valido,
               updated_at = :updated_at
         WHERE id = :id");

        $isValidCpf = (int) $userEntity->getIsValidCpf();
        $stmt->execute([
            ':nome' => $userEntity->getName(),
            ':cpf' => $userEntity->getCpf(),
            ':valido' => $isValidCpf,
            ':updated_at' => $now,
            ':id' => $userEntity->getId()]);

            if ($stmt->rowCount() != 1) {
            throw new Exception("Error updating user");
        }
    }

    public function delete(string $id): void
    {
        $intId = (int) $id;
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $intId, SQLITE3_INTEGER);
        $stmt->execute();

        if ($stmt->rowCount() != 1) {
            throw new Exception("Error deleting user");
        }
    }

    public function findById(string $id): IUserEntity
    {
        $intId = (int) $id;
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $intId, SQLITE3_INTEGER);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if (empty($result)) {
            throw new Exception("User not found");
        }

        $this->resetUserEntity();
        $this->populateEntityByArray($result);
        return $this->userEntity;
    }

    public function count(): int
    {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM users");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    private function resetUserEntity(): void
    {
        $this->userEntity->setId('');
        $this->userEntity->setName('');
        $this->userEntity->setCpf('');
        $this->userEntity->setIsValidCpf(false);
    }

    private function populateEntityByArray(array $data): void
    {
        $this->userEntity->setId($data['id']);
        $this->userEntity->setName($data['nome']);
        $this->userEntity->setCpf($data['cpf']);
        $this->userEntity->setIsValidCpf((bool) $data['valido']);
    }
}
