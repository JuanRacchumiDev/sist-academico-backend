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

class UserService implements IUserService {
    protected IUserRepository $userRepository;
    protected IPersonaRepository $personaRepository;
    protected IDetalleParametroRepository $detalleParametroRepository;

    public function __construct(
        IUserRepository $userRepository,
        IPersonaRepository $personaRepository,
        IDetalleParametroRepository $detalleParametroRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->personaRepository = $personaRepository;
        $this->detalleParametroRepository = $detalleParametroRepository;
    }

    /**
     * Obtiene todos los usuarios
     * @return Collection<int, User>
     */
    public function getAllUsers(): Collection
    {
        return $this->userRepository->getAll();
    }

    /**
     * Obtiene todos los usuarios con filtros aplicados
     * @param array<string, mixed> $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllUsersWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->getAllFiltered($filters, $perPage);
    }

    /**
     * Obtiene un usuario por ID
     * @param int $id
     * @return User|null
     */
    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    /**
     * Obtiene un usuario por email
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return $this->userRepository->findByEmail($email);
    }

    /**
     * Valida las credenciales y maneja el inicio de sesión
     * @param string $email
     * @param string $password
     * @param int|null $idPerfil
     * @return array<string, mixed>
     * @throws ValidationException
     */
    public function login(string $email, string $password, ?int $idPerfil = null): array
    {
        // Obtiene todos los usuarios asociados a un email
       $users = $this->userRepository->findAllByEmail($email);
       
       if ($users->empty()) {
            throw ValidationException::withMessages([
                'email' => 'Credenciales inválidas'
            ]);
       }

       $firstUser = $users->first();
       
       if (Hash::check($password, $firstUser->password)) {
            throw ValidationException::withMessages([
                'password' => 'Credenciales inválidas'
            ]);
       }

       /** @var \Tymon\JWTAuth\JWTGuard $guard */
       $guard = auth('api');

       // Validar la existencia de perfiles
       if ($users->count() > 1) {
            if (is_null($idPerfil)) {
                return [
                    'success' => true,
                    'requires_profile_selection' => true,
                    'profiles' => $users->map(function(User $user) {
                        return [
                            'id' => $user->id_perfil,
                            'name' => $user->perfil->nombre ?? 'Perfil desconocido'
                        ];
                    })->toArray()
                ];
            }

            // Buscar el usuario especificado, si perfil ID existe
            $user = $users->first(fn(User $user) => $user->id_perfil === $idPerfil);

            if (!$user) {
                throw ValidationException::withMessages([
                    'idPerfil' => 'Perfil no asociado a las credenciales proporcionadas.'
                ]);
            }

            // Inicio de sesión exitoso con perfil seleccionado
            return [
                'success' => true,
                'user' => $user,
                'token' => $guard->login($user) // Asumiendo autenticación JWT (Tymon\JWTAuth)
            ];
        } else {
            // Solo existe un perfil, iniciar sesión automáticamente.
            $user = $firstUser;

            // Inicio de sesión exitoso (único perfil)
            return [
                'success' => true,
                'user' => $user,
                'token' => $guard->login($user) // Asumiendo autenticación JWT
            ];
        }
    }

    /**
     * Crea un nuevo usuario
     * @param UserCreateDTO $userCreateDTO
     * @return User
     */
    public function createUser(UserCreateDTO $userCreateDTO): User
    {
        $data = array_filter($userCreateDTO->toArray(), fn($value) => !is_null($value));

        // Verifica si existe usuario con name, email y tipo de perfil
        $name = $data['name'];
        $email = $data['email'];
        $newIdPerfil = $data['idPerfil'];

        $existUserByNameAndEmailAndPerfilId = $this->userRepository->findByNameAndEmailAndPerfil($name, $email, $newIdPerfil);

        if ($existUserByNameAndEmailAndPerfilId) {
            // $perfil = $this->perfilRepository->findById($newIdPerfil);
            $perfil = $this->detalleParametroRepository->findByCodigo($newIdPerfil);

            throw ValidationException::withMessages([
                'perfilId' => "El nombre de usuario y email ya están asociados con el perfil '{$perfil->nombre}'."
            ]);
        }
        
        // Validando si el email está asociado a un nombre de usuario
        $existUserByEmail = $this->userRepository->findByEmail($email);

        if ($existUserByEmail && $existUserByEmail->name !== $name) {
            throw ValidationException::withMessages([
                'email' => "El email está asociado a otro nombre de usuario"
            ]);
        }

        return $this->userRepository->create($data);
    }

    /**
     * Actualiza un usuario existente
     * @param UserUpdateDTO $userUpdateDTO
     * @return User|null
     */
    public function updateUser(UserUpdateDTO $userUpdateDTO): ?User
    {
        $updatedCount = 0;
        
        $user = $this->userRepository->findById($userUpdateDTO->id);

        if (!$user) {
            throw ValidationException::withMessages([
                'usuario' => "Usuario no encontrado"
            ]);
        }

        if ($userUpdateDTO->persona) {
            $existingPersona = $this->personaRepository->findByTipoDocAndNumDoc(
                $userUpdateDTO->persona->id_tipodocumento,
                $userUpdateDTO->persona->numero_documento
            );

            if ($existingPersona && $existingPersona->id !== $user->persona?->id) {
                throw ValidationException::withMessages([
                    'persona.cNumeroDocumento' => 'El número de documento ya está en uso por otra persona.'
                ]);
            }

            if ($userUpdateDTO->persona->nombres || $userUpdateDTO->persona->apellido_paterno || $userUpdateDTO->persona->apellido_materno) {
                $userUpdateDTO->persona->nombre_completo = trim(
                    ($userUpdateDTO->persona->nombres ?? $user->persona?->nombres ?? '') . ' ' .
                    ($userUpdateDTO->persona->apellido_paterno ?? $user->persona?->apellido_paterno ?? '') . ' ' .
                    ($userUpdateDTO->persona->apellido_materno ?? $user->persona?->apellido_materno ?? '')
                );
            }

            $personaData = array_filter($userUpdateDTO->persona->toArray(), fn($value) => !is_null($value));

            $this->personaRepository->update($userUpdateDTO->persona->id, $personaData);
        }

        $userData = array_filter($userUpdateDTO->toArray(), fn($value) => !is_null($value));

        if (isset($userData['email'])) {
            $newEmail = $userData['email'];

            if ($user->email === $newEmail) {
                $updatedCount = User::where('email', $user->email)->update(['email' => $newEmail]);
            } else {
                $existUserByEmail = $this->userRepository->findByEmail($newEmail);

                if ($existUserByEmail) {
                    throw ValidationException::withMessages([
                        'email' => "El email está asociado a otro nombre de usuario"
                    ]);
                }

                $updatedCount = User::where('email', $user->email)->update(['email' => $newEmail]);
            }
    
            if ($updatedCount > 0) {
                return $this->userRepository->findById($userUpdateDTO->id);
            } else {
                return $user;
            }
        }

        return $this->userRepository->update($userUpdateDTO->id, $userData);
    }

    /**
     * Elimina un usuario
     * @param int $id
     * @return bool
     * @throws \Exception
     */
    public function deleteUser(int $id): bool
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return false;
        }

        return $this->userRepository->delete($id);
    }
}