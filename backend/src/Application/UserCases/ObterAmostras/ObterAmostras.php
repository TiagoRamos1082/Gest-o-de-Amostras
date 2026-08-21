<?php

namespace App\Application\UserCases\ObterAmostras;

use App\Application\Repositories\AmostraRepository;

class ObterAmostras
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(): array
    {
        return $this->repository->obterAmostras();
    }
}
