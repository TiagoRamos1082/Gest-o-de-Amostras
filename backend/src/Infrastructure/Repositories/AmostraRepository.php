<?php

namespace App\Infrastructure\Repositories;

use App\Application\Repositories\AmostraRepository as AmostraRepositoryInterface;
use App\Domain\Amostra;
use PDO;

class AmostraRepository implements AmostraRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function obterProximoSequencial(int $ano): int
    {
        $sql = "
            SELECT MAX(
                CAST(
                    SUBSTRING_INDEX(codigo, '-', -1)
                    AS SIGNED
                )
            )
            FROM amostras
            WHERE CAST(
                SUBSTRING_INDEX(
                    SUBSTRING_INDEX(codigo, '-', 2),
                    '-',
                    -1
                ) AS UNSIGNED
            ) = :ano
           ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'ano' => $ano
        ]);

        $ultimoSequencial = $stmt->fetchColumn();

        if($ultimoSequencial === null)
        {
            return 1;
        }

        return (int) $ultimoSequencial + 1;

    }

    public function obterPorId(int $id): array|false
    {
        $sql = '
            SELECT *
            FROM amostras
            WHERE id = :id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $dados = $stmt->fetch();

        return $dados;
    }

    public function salvar(Amostra $amostra): void
    {
        $sql = "
            INSERT INTO amostras
                (codigo, tipo, status, responsavel_tecnico, data_recebimento)
            values
                ( :codigo, :tipo, :status, :responsavel_tecnico, :data_recebimento);
            ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'codigo' => $amostra->obterCodigo(),
            'tipo' => $amostra->obterTipo(),
            'status' => $amostra->obterStatus(),
            'responsavel_tecnico' => $amostra->obterResponsavelTecnico(),
            'data_recebimento' => $amostra->obterDataRecebimento()->format('Y-m-d')
        ]);

        $sql = "
            SELECT id FROM amostras where codigo = :codigo
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([
            'codigo' => $amostra->obterCodigo()
        ]);

        $resultado = $stmt->fetch();

        $amostra->definirId($resultado['id']);
    }

    public function deletar(int $id): void
    {
        $sql = '
            delete from amostras where id = :id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

    }

    public function atualizar(Amostra $amostra): void
    {

        $sql = '
            UPDATE amostras
            SET
                tipo = :tipo,
                status = :status,
                responsavel_tecnico = :responsavel_tecnico,
                data_conclusao = :data_conclusao,
                bloqueada = :bloqueado
            WHERE id = :id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'tipo' => $amostra->obterTipo(),
            'status' => $amostra->obterStatus(),
            'responsavel_tecnico' => $amostra->obterResponsavelTecnico(),
            'data_conclusao' => $amostra->obterDataConclusao(),
            'bloqueado' => $amostra->estaBloqueada() ? 1 : 0,
            'id' => $amostra->obterId()
        ]);


    }


}


?>
