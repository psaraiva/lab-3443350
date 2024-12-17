<?php

namespace App\Adapters\Out\RepositoryJson;

use App\Adapters\Out\Repository\Entity\IUserEntity;
use App\Adapters\Out\Repository\IUserRepository;

use \Exception;

class UserRepositoryJson implements IUserRepository
{
    private $userEntity = null;
    private string $dir = '';
    private string $idFileControl = '';

    const FILE_NAME_PATTERN = '/^\d+\.json$/';

    public function __construct(IUserEntity $userEntity)
    {
        $dir = APP_PATH . '/data/json/';
        $this->checkDirectory($dir);
        $this->dir = $dir;
        $this->userEntity = $userEntity;
        $this->idFileControl = $dir . 'id.json';
    }

    private function checkDirectory(string $dir): void
    {
        if (!is_dir($dir)) {
            throw new Exception("Failed directory not found.");
        }

        if (!is_writable($dir)) {
            throw new Exception("Failed directory not writable.");
        }
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
        $id = $this->getNextId();
        $userEntity->setId($id);
        $json = json_encode($userEntity->toArray());

        $jsonFileName = $this->dir . $id . '.json';
        if (file_put_contents($jsonFileName, $json) === false) {
            throw new Exception("Failed to write file.");
        }

        $this->indexIncrement();
    }

    public function update(IUserEntity $userEntity): void
    {
        $id = $userEntity->getId();
        $this->delete($id);

        $json = json_encode($userEntity->toArray());
        $jsonFileName = $this->dir . $id . '.json';
        if (file_put_contents($jsonFileName, $json) === false) {
            throw new Exception("Failed to write file.");
        }
    }

    public function delete(string $id): void
    {
        $jsonFileName = $this->dir . $id . '.json';
        if (!file_exists($jsonFileName)) {
            throw new Exception("Failed to open file.");
        }

        if (!unlink($jsonFileName)) {
            throw new Exception("Failed to delete file.");
        }
    }

    public function findById(string $id): IUserEntity
    {
        return $this->userEntity;
    }

    public function getNextId(): int
    {
        if (!file_exists($this->idFileControl)) {
            throw new Exception("Failed to open id file.");
        }

        $jsonContent = file_get_contents($this->idFileControl);
        if ($jsonContent === false) {
            throw new Exception("Failed to read file id file.");
        }

        $data = json_decode($jsonContent, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Failed to decode JSON: " . json_last_error_msg());
        }

        return $data['id'] + 1;
    }

    private function indexIncrement(): void
    {
        $json = json_encode(['id' => $this->getNextId()]);
        if (file_put_contents($this->idFileControl, $json) === false) {
            throw new Exception("Failed to write file.");
        }
    }

    public function isCpfExists(string $cpf): bool
    {
        $files = scandir($this->dir);
        foreach ($files as $file) {
            if (! preg_match(self::FILE_NAME_PATTERN, $file)) {
                continue;
            }

            $jsonContent = file_get_contents($this->dir.$file);
            if ($jsonContent === false) {
                throw new Exception("Failed to read file id file.");
            }
    
            $data = json_decode($jsonContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                var_dump($this->dir . $file);
                throw new Exception("Failed to decode JSON: " . json_last_error_msg());
            }

            if ($cpf == $data['cpf']) {
                return true;
            }
        }

        return false;
    }

    public function count(): int
    {
        $total = 0;
        $files = scandir($this->dir);
        foreach ($files as $file) {
            if (preg_match(self::FILE_NAME_PATTERN, $file)) {
                $total++;
            }
        }

        return $total;
    }
}
