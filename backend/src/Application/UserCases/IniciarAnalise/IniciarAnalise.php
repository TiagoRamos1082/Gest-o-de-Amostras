<?php

namespace App\Application\UserCases\IniciarAnalise;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class IniciarAnalise
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra): bool
    {
        $amostra->iniciarAnalise();

        $this->repository->atualizar($amostra);

        return true;
    }
}


?>
