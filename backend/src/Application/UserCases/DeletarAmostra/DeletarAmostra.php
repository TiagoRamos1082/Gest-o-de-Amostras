<?php

namespace App\Application\UserCases\DeletarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class DeletarAmostra
{
    private AmostraRepository $repository;


    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra): bool
    {
        $id = $amostra->obterId();

        $this->repository->deletar($id);

        return true;
    }
}
