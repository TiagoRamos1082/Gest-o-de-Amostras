<?php

    namespace App\Application\Repositories;

    use App\Domain\Amostra;

    interface AmostraRepository
    {
        public function obterProximoSequencial(int $ano): int;

        public function obterPorId(int $id): array|false;

        public function obterAmostraPorId(int $id): Amostra|false;

        public function obterAmostras(): array;

        public function salvar(Amostra $amostra): void;

        public function deletar(int $id): void;

        public function atualizar(Amostra $amostra): void;
    }
?>
