<?php

namespace Tests\Infrastructure\Repositories;

use App\Domain\Amostra;
use App\Infrastructure\DataBase\Connection;
use App\Infrastructure\Repositories\AmostraRepository;
use PDO;
use PHPUnit\Framework\TestCase;

class AmostraRepositoryTest extends TestCase
{
    private AmostraRepository $repository;
    private PDO $connection;

    protected function setUp(): void
    {
        $this->connection = Connection::create();

        $this->repository = new AmostraRepository($this->connection);
    }

    public function testDeveRetornarProximoSequencialDoAno(): void
    {
        $resultado = $this->repository->obterProximoSequencial(2026);

        $this->assertIsInt($resultado);
    }

    public function testDeveCriarPersistenciaAmostra(): void
    {
        $amostra = new Amostra(
            tipo: "Água",
            responsavel_tecnico: "João",
            data_recebimento: "2026-08-17",
        );

        $sequencial = $this->repository->obterProximoSequencial(
            $amostra->obterAnoDataRecebimento()
        );

        $amostra->definirCodigo($sequencial);

        $this->repository->salvar($amostra);

        $sql = "
            SELECT * FROM amostras WHERE codigo = :codigo
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'codigo' => $amostra->obterCodigo()
        ]);

         $resultado = $stmt->fetch();

         $this->assertNotFalse($resultado);
         $this->assertSame($amostra->obterId(), $resultado['id']);
         $this->assertSame($amostra->obterCodigo(), $resultado['codigo']);
         $this->assertSame($amostra->obterTipo(), $resultado['tipo']);
         $this->assertSame($amostra->obterStatus(), $resultado['status']);


         $sql = "
             DELETE FROM amostras WHERE codigo = :codigo
         ";

         $stmt = $this->connection->prepare($sql);

         $stmt->execute([
             'codigo' => $amostra->obterCodigo()
         ]);

    }

    public function testDeveDeletarPersistenciaAmostra(): void
    {
        $amostra = new Amostra(
            tipo: "Água",
            responsavel_tecnico: "João",
            data_recebimento: "2026-08-17",
        );

        $sequencial = $this->repository->obterProximoSequencial(
            $amostra->obterAnoDataRecebimento()
        );

        $amostra->definirCodigo($sequencial);

        $this->repository->salvar($amostra);

        $id = $amostra->obterId();

        $this->repository->deletar($id);

        $sql = '
            SELECT id
            FROM amostras
            WHERE id = :id
        ';

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id' => $id
        ]);

        $resultado = $stmt->fetch();

        $this->assertFalse($resultado);
    }

    public function testDeveAtualizarAmostra(): void
    {
        $amostra = new Amostra(
            tipo: "Água",
            responsavel_tecnico: "João",
            data_recebimento: "2026-08-17",
        );

        $sequencial = $this->repository->obterProximoSequencial(
            $amostra->obterAnoDataRecebimento()
        );

        $amostra->definirCodigo($sequencial);

        $this->repository->salvar($amostra);

        // Altera a amostra
        $amostra->definirTipo("Solo");

        $this->repository->atualizar($amostra);

        // Busca novamente no banco
        $sql = "
            SELECT * FROM amostras WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id' => $amostra->obterId()
        ]);

        $resultado = $stmt->fetch();

        $this->assertNotFalse($resultado);
        $this->assertSame("Solo", $resultado['tipo']);
        $this->assertSame($amostra->obterStatus(), $resultado['status']);
        $this->assertSame(
            $amostra->obterResponsavelTecnico(),
            $resultado['responsavel_tecnico']
        );

        // Limpeza
        $sql = "
            DELETE FROM amostras WHERE id = :id
        ";

        $stmt = $this->connection->prepare($sql);

        $stmt->execute([
            'id' => $amostra->obterId()
        ]);
    }
}

?>
