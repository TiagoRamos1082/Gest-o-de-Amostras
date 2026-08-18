<?php

namespace App\Application\UserCases\CriarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class CriarAmostra
{
    private AmostraRepository $repository;


    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(array $dados): Amostra
    {
        $amostra = new Amostra(
            $dados["tipo"],
            $dados["responsavelTecnico"],
            $dados["dataRecebimento"]
        );

        $sequencial = $this->repository->obterProximoSequencial(
            $amostra->obterAnoDataRecebimento()
        );

        $amostra->definirCodigo($sequencial);

        $this->repository->salvar($amostra);

        return $amostra;
    }
}
