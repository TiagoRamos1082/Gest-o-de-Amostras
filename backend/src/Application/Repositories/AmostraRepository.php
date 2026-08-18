<?php

    namespace App\Application\Repositories;

    use App\Domain\Amostra;

    interface AmostraRepository
    {
        public function obterProximoSequencial(int $ano): int;

        public function salvar(Amostra $amostra): void;

        public function deletar(int $id): void;
    }
?>
