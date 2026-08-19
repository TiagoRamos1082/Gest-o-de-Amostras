<?php

namespace App\Application\UserCases\AtualizarResponsavelTecnico;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class AtualizarResponsavelTecnico
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra, ?string $tecnico): bool
    {
        $amostra->definirTecnico($tecnico);

        $this->repository->atualizar($amostra);

        return true;
    }
}
