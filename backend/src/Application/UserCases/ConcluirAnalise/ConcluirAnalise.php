<?php

namespace App\Application\UserCases\ConcluirAnalise;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class ConcluirAnalise
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(
        Amostra $amostra,
        string $dataConclusao
    ): bool {
        $amostra->concluirAnalise($dataConclusao);

        $this->repository->atualizar($amostra);

        return true;
    }
}


?>
