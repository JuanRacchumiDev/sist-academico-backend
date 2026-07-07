<?php

namespace App\Services;

use App\DTOs\User\UserCreateDTO;
use App\DTOs\User\UserUpdateDTO;
use App\Models\User;
use App\Repositories\Contracts\IUserRepository;
use App\Services\Contracts\IUserService;
use Illuminate\Support\Facades\DB;
use App\Mail\UserWelcomeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class UserService implements IUserService
{
    protected IUserRepository $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(array $filters = [], int $perPage = 10)
    {
        return $this->userRepository->getAll($filters, $perPage);
    }

    public function getAllUsersWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getAllFiltered($filters, $perPage);
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
        return DB::transaction(function () use ($userCreateDTO) {
            // Extraemos la contraseña directamente de la propiedad del DTO
            $temporaryPassword = $userCreateDTO->password;

            Log::info('Iniciando creación de usuario', [
                'dto_password' => $temporaryPassword
            ]);

            // Convertimos el DTO a array
            $data = $userCreateDTO->toArray();

            Log::info('data nuevo usuario', ['data' => $data]);

            // Pasamos los datos al repositorio
            $user = $this->userRepository->create($data);

            Log::info('nuevo usuario', ['user' => $user]);

            // Enviamos el correo electrónico
            Mail::to($user->email)->send(new UserWelcomeMail($user, $temporaryPassword));

            return $user;
        });
    }

    public function updateUser(int $id, UserUpdateDTO $userUpdateDTO): ?User
    {
        $data = array_filter($userUpdateDTO->toArray(), fn($value) => !is_null($value));

        return $this->userRepository->update($id, $data);
    }
}
