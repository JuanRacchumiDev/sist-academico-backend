<?php

namespace App\Services;

use App\DTOs\Cuestionario\CuestionarioCreateDTO;
use App\DTOs\Cuestionario\CuestionarioUpdateDTO;
use App\Models\Cuestionario;
use App\Repositories\Contracts\ICuestionarioRepository;
use App\Repositories\Contracts\IPreguntaRepository;
use App\Services\Contracts\ICuestionarioService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Override;

class CuestionarioService implements ICuestionarioService
{
    protected ICuestionarioRepository $cuestionarioRepository;
    protected IPreguntaRepository $preguntaRepository;

    public function __construct(ICuestionarioRepository $cuestionarioRepository)
    {
        $this->cuestionarioRepository = $cuestionarioRepository;
    }

    public function getAllCuestionarios(?array $searchParams = null): Collection
    {
        return $this->cuestionarioRepository->getAll($searchParams);
    }

    public function getAllCuestionariosWithFilters(array $filters, int $perPage): LengthAwarePaginator
    {
        return $this->cuestionarioRepository->getAllFiltered($filters, $perPage);
    }

    public function getCuestionarioById(int $id): ?Cuestionario
    {
        return $this->cuestionarioRepository->findById($id);
    }

    public function createCuestionario(CuestionarioCreateDTO $cuestionarioCreateDTO): Cuestionario
    {
        return DB::transaction(function () use ($cuestionarioCreateDTO) {
            Log::info('Evaluando variable $cuestionarioCreateDTO', ['cuestionarioCreateDTO' => $cuestionarioCreateDTO]);

            $dataToCreate = $cuestionarioCreateDTO->toArray();
            Log::info('Evaluando variable $dataToCreate', ['dataToCreate' => $dataToCreate]);

            // Obtener el usuario creador
            $userCrea = $cuestionarioCreateDTO->user_crea ?? 'systemapi';

            Log::info('Evaluando variable $userCrea', ['userCrea' => $userCrea]);

            $cuestionarioData = array_filter($cuestionarioCreateDTO->except('preguntas')->toArray(), fn($value) => !is_null($value));

            $cuestionario = $this->cuestionarioRepository->create($cuestionarioData);

            if (!empty($cuestionarioCreateDTO->preguntas)) {
                $preguntasArray = array_map(fn($p) => array_filter($p->toArray(), fn($v) => !is_null($v)), $cuestionarioCreateDTO->preguntas);
                $this->preguntaRepository->createMany($cuestionario->id, $preguntasArray);
            }

            return $cuestionario->load('preguntas');

            // Filtrar nulos
            // $data = array_filter($dataToCreate, fn($value) => !is_null($value));

            // Crear el cuestionario
            /** @var Cuestionario $cuestionario */
            // $cuestionario = $this->cuestionarioRepository->create($data);

            // return $cuestionario;
        });
    }

    public function updateCuestionario(int $id, CuestionarioUpdateDTO $cuestionarioUpdateDTO): ?Cuestionario
    {
        return DB::transaction(function () use ($id, $cuestionarioUpdateDTO) {
            $data = array_filter($cuestionarioUpdateDTO->except('preguntas')->toArray(), fn($value) => !is_null($value));

            $cuestionario = $this->cuestionarioRepository->update($id, $data);

            if ($cuestionario && isset($cuestionarioUpdateDTO->preguntas)) {
                $preguntasArray = array_map(fn($p) => array_filter($p->toArray(), fn($v) => !is_null($v)), $cuestionarioUpdateDTO->preguntas);
                $this->preguntaRepository->syncPreguntas($id, $preguntasArray);
            }

            return $cuestionario?->load('preguntas');
        });

        // $data = array_filter($cuestionarioUpdateDTO->toArray(), fn($value) => !is_null($value));

        // return $this->cuestionarioRepository->update($id, $data);
    }

    public function deleteCuestionario(int $id): bool
    {
        $cuestionario = $this->cuestionarioRepository->findById($id);

        if (!$cuestionario) {
            return false;
        }

        return $this->cuestionarioRepository->delete($id);
    }
}
