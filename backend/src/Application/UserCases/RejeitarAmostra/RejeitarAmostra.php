<?php

namespace App\Application\UserCases\RejeitarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class RejeitarAmostra
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra): bool
    {
        $amostra->rejeitarAmostra();

        $this->repository->atualizar($amostra);

        return true;
    }
}
