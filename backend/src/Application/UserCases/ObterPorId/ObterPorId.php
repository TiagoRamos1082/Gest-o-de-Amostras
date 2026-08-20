<?php

namespace App\Application\UserCases\ObterPorId;

use App\Application\Repositories\AmostraRepository;

class ObterPorId
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(int $id): array|false
    {
        return $this->repository->obterPorId($id);
    }
}
