<?php

namespace App\Adapters\In\Cli\Controller;

// Adapters
use App\Adapters\In\Cli\Controller\Mapper\UserMapper as UserControllerMapper;
use App\Adapters\Out\DeleteUserByIdAdapter;
use App\Adapters\Out\InsertUserAdapter;
use App\Adapters\Out\FindUserByIdAdapter;
use App\Adapters\Out\UpdateUserAdapter;
use App\Adapters\Out\Repository\IUserRepository;
use App\Adapters\Out\Repository\Mapper\IUserEntityMapper;
// Use Cases
use App\Application\Core\UseCase\FindUserByIdUseCase;
use App\Application\Core\UseCase\InsertUserUseCase;
use App\Application\Core\UseCase\UpdateUserUseCase;
use App\Application\Core\UseCase\DeleteUserByIdUserCase;
// others
use Exception;

class UserController
{
    /**
     * Default response data
     *
     * @var array
     */
    private $dataResponse  = [
        'data' => '',
        'status' => '',
        'message' => '',
        'errors' => [],
    ];

    // Mapper Controllers
    private UserControllerMapper $userControllerMapper;
    // Use Cases
    private InsertUserUseCase $insertUseCase;
    private FindUserByIdUseCase $findByIdUseCase;
    private UpdateUserUseCase $updateUseCase;
    private DeleteUserByIdUserCase $deleteByIdUseCase;

    public function __construct(
        IUserRepository $userRepository,
        IUserEntityMapper $userEntityMapper)
    {
        $this->userControllerMapper = new UserControllerMapper();
        $this->insertUseCase = new InsertUserUseCase(new InsertUserAdapter($userRepository, $userEntityMapper));
        $this->findByIdUseCase = new FindUserByIdUseCase(new FindUserByIdAdapter($userRepository, $userEntityMapper));
        $this->updateUseCase = new UpdateUserUseCase(new UpdateUserAdapter($userRepository, $userEntityMapper));
        $this->deleteByIdUseCase = new DeleteUserByIdUserCase(new DeleteUserByIdAdapter($userRepository, $userEntityMapper));
    }

    public function insert(string $json): string
    {
        try {
            $user = $this->userControllerMapper->toUser($json);
            $this->insertUseCase->insert($user);
            $this->dataResponse['status'] = 'success';
            $this->dataResponse['message'] = 'User inserted successfully';
        } catch (Exception $e) {
            $this->dataResponse['status'] = 'error';
            $this->dataResponse['message'] = 'Error inserting user';
            $this->dataResponse['errors'] = [$e->getMessage()];
        }

        return json_encode($this->dataResponse);
    }

    public function findById(string $json): string
    {
        try {
            $user = $this->userControllerMapper->toUser($json);
            $user = $this->findByIdUseCase->find($user->getId());
            $jsonResponse = $this->userControllerMapper->toUserResponse($user)->toJson();
            $this->dataResponse['data'] = $jsonResponse;
            $this->dataResponse['status'] = 'success';
            $this->dataResponse['message'] = 'User found successfully';
        } catch (Exception $e) {
            $this->dataResponse['status'] = 'error';
            $this->dataResponse['message'] = 'Error searching for user';
            $this->dataResponse['errors'] = [$e->getMessage()];
        }

        return json_encode($this->dataResponse);
    }

    public function update(string $json): string
    {
        try {
            $user = $this->userControllerMapper->toUser($json);
            $this->updateUseCase->update($user);
            $this->dataResponse['status'] = 'success';
            $this->dataResponse['message'] = 'User updated successfully';
        } catch (Exception $e) {
            $this->dataResponse['status'] = 'error';
            $this->dataResponse['message'] = 'Error updating user';
            $this->dataResponse['errors'] = [$e->getMessage()];
        }

        return json_encode($this->dataResponse);
    }

    public function deleteById(string $json): string
    {
        try {
            $id = $this->userControllerMapper->toUser($json)->getId();
            if ((int) $id < 1) {
                throw new Exception("User ID invalid");
            }

            $this->deleteByIdUseCase->delete($id);
            $this->dataResponse['status'] = 'success';
            $this->dataResponse['message'] = 'User deleted successfully';
        } catch (Exception $e) {
            $this->dataResponse['status'] = 'error';
            $this->dataResponse['message'] = 'Error deleting user';
            $this->dataResponse['errors'] = [$e->getMessage()];
        }

        return json_encode($this->dataResponse);
    }
}
