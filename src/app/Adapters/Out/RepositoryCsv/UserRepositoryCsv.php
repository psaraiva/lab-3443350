<?php

declare(strict_types=1);

namespace App\Adapters\Out\RepositoryCsv;

use App\Adapters\Out\Repository\Entity\IUserEntity;
use App\Adapters\Out\Repository\IUserRepository;

use \Exception;

class UserRepositoryCsv implements IUserRepository
{
    private $file = null;
    private $resource = null;
    private $userEntity = null;

    public function __construct(IUserEntity $userEntity)
    {
        $this->userEntity = $userEntity;
        $csv = APP_PATH . '/data/csv/usuarios.csv';
        if (!file_exists($csv)) {
            throw new Exception("File 'CSV not found.");
        }

        $this->file = $csv;
    }

    public function __destruct()
    {
        $this->closeResource();
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
        if ($this->isCpfExists($userEntity->getCpf())) {
            throw new Exception("CPF already exists.");
        }

        $data = [
            $this->getNextId(),
            $userEntity->getName(),
            $userEntity->getCpf(),
            ($userEntity->getIsValidCpf()) ? 'true' : 'false',
        ];

        $this->saveByArray($data);
    }

    public function update(IUserEntity $userEntity): void
    {
        $data = [
            $userEntity->getId(),
            $userEntity->getName(),
            $userEntity->getCpf(),
            $userEntity->getIsValidCpf() ? 'true' : 'false',
        ];

        $rows = [];
        $id = $userEntity->getId();
        $this->startResource('r');
        while (($line = fgets($this->resource)) !== false) {
            $row = str_getcsv($line);
            if ($row[0] != $id) {
                $rows[] = $row;
                continue;
            }

            $isExists = true;
            $rows[] = $data;
        }
        $this->closeResource();

        if (!$isExists) {
            throw new Exception("User not found.");
        }
        $this->saveByArray($rows, 'w');
    }

    public function delete(string $id): void
    {
        $rows = [];
        $this->startResource('r');
        while (($line = fgets($this->resource)) !== false) {
            $row = str_getcsv($line);
            if ($row[0] != $id) {
                $rows[] = $row;
                continue;
            }
        }
        $this->closeResource();
        $this->saveByArray($rows, 'w');
    }

    public function findById(string $id): IUserEntity
    {
        $this->startResource();
        $header = 0;
        $isExists = false;
        while (($line = fgets($this->resource)) !== false) {
            if ($header == 0) {
                $header++;
                continue;
            }

            $row = str_getcsv($line);
            if (count($row) < 4) {
                throw new Exception("Invalid row CSV file.");
                continue;
            }

            if ($row[0] == $id) {
                $isExists = true;
                break;
            }
        }
        $this->closeResource();

        $this->resetUserEntity();
        if (!$isExists) {
            return $this->userEntity;
        }

        $this->userEntity->setId($row[0]);
        $this->userEntity->setName($row[1]);
        $this->userEntity->setCpf($row[2]);
        $this->userEntity->setIsValidCpf((bool) $row[3]);
        return $this->userEntity;
    }

    public function count(): int
    {
        $rowCount = 0;
        $this->startResource();
        while (fgets($this->resource) !== false) {
            $rowCount++;
        }

        $this->closeResource();
        return $rowCount - 1; // 1st row header, not count
    }

    public function getNextId(): int
    {
        $lastLine = '';
        $this->startResource();
        while (($line = fgets($this->resource)) !== false) {
            $lastLine = $line;
        }
        $this->closeResource();

        $row = str_getcsv($lastLine);
        if (empty($row) || count($row) == 0) {
            return 1;
        }

        return ((int) $row[0]) + 1;
    }

    public function isCpfExists(string $cpf): bool
    {
        $this->startResource();
        while (($line = fgets($this->resource)) !== false) {
            $row = str_getcsv($line);
            if ($row[2] == $cpf) {
                return true;
            }
        }

        $this->closeResource();
        return false;
    }

    private function resetUserEntity(): void
    {
        $this->userEntity->setId('');
        $this->userEntity->setName('');
        $this->userEntity->setCpf('');
        $this->userEntity->setIsValidCpf(false);
    }

    private function startResource($mode = 'r')
    {
        if ($this->resource === null) {
            $this->resource = fopen($this->file, $mode);
            if ($this->resource === false) {
                throw new Exception("File SCV not found.");
            }
        }

        return $this->resource;
    }

    private function closeResource(): void
    {
        if ($this->resource !== null) {
            fclose($this->resource);
            $this->resource = null;
        }
    }

    private function saveByArray(array $data, $mode = 'a'): void
    {
        $content = is_array($data[0]) ? $data : [$data];
        $this->startResource($mode);
        foreach ($content as $row) {
            fputcsv($this->resource, $row);
        }
        $this->closeResource();
    }
}
