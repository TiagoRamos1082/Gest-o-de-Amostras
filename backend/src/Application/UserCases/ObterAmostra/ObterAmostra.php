<?php

namespace App\Application\UserCases\ObterAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;

class ObterAmostra
{
    private AmostraRepository $repository;

    public function __construct(AmostraRepository $repository)
    {
        $this->repository = $repository;
    }

    public function executar(int $id): Amostra|false
    {
        return $this->repository->obterAmostraPorId($id);
    }
}
