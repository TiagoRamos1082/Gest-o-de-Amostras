<?php

namespace Tests\Application\UserCases\ObterPorId;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\ObterPorId\ObterPorId;
use PHPUnit\Framework\TestCase;

class ObterPorIdTest extends TestCase
{
    public function testDeveObterAmostraPorId(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->once())
            ->method('obterPorId')
            ->with(1)
            ->willReturn([
                'id' => 1,
                'codigo' => 'CAND108-2026-000001',
                'tipo' => 'Água',
                'status' => 'Recebida',
                'responsavel_tecnico' => 'João Silva',
                'data_recebimento' => '2026-08-19',
                'data_conclusao' => null,
                'bloqueada' => 0
            ]);

        $useCase = new ObterPorId($repository);

        $resultado = $useCase->executar(1);

        $this->assertIsArray($resultado);
        $this->assertSame(1, $resultado['id']);
        $this->assertSame('CAND108-2026-000001', $resultado['codigo']);
        $this->assertSame('Água', $resultado['tipo']);
    }
}
