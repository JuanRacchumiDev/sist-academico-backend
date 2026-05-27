<?php
namespace App\Services;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Models\User;
use App\Repositories\Contracts\IPersonaRepository;
use App\Repositories\Contracts\IUserRepository;
use App\Repositories\Contracts\IDetalleParametroRepository;
use App\Services\Contracts\IUserService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;

class UserService implements IUserService {
    protected IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters = [], int $perPage = 10)
    {
        return $this->userRepository->get($filters, $perPage);
    }

    public function getUserById(int $id): ?User
    {
        $filters = ['id' => $id];

        return $this->userRepository->findOne($filters);
    }
    
    public function getUserByParams(array $filters = [])
    {
        return $this->userRepository->findOne($filters);
    }

    public function createUser(UserCreateDTO $userCreateDTO): User
    {
        return DB::transaction(function() use ($userCreateDTO) {
            $dtoData = $userCreateDTO->toArray();
            $data = array_filter($dtoData, fn($value) => !is_null($value));
            return $this->userRepository->create($data);
        });
    }

    public function updateUser(int $id, UserUpdateDTO $userUpdateDTO): ?User
    {
        $data = array_filter($userUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->userRepository->update($id, $data);
    }
}