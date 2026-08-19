<?php

namespace App\Application\UserCases\AtualizarTipoAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class AtualizarTipoAmostra
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra, string $tipo): bool
    {
        $amostra->definirTipo($tipo);

        $this->repository->atualizar($amostra);

        return true;
    }
}

?>
