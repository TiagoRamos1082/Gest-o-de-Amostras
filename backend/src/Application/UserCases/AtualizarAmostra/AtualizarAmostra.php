<?php


namespace App\Application\UserCases\AtualizarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class AtualizarAmostra
{
    private AmostraRepository $repository;


    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(Amostra $amostra): bool {

        $this->repository->atualizar($amostra);

        return true;
    }
}

?>
