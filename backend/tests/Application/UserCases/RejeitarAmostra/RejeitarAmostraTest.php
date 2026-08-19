<?php

namespace Tests\Application\UserCases\RejeitarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\RejeitarAmostra\RejeitarAmostra;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class RejeitarAmostraTest extends TestCase
{
    public function testDeveRejeitarAmostra(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $amostra = new Amostra(
            tipo: 'Água',
            responsavel_tecnico: 'João',
            data_recebimento: '2026-08-18'
        );

        $amostra->definirId(1);

        $repository
            ->expects($this->once())
            ->method('atualizar')
            ->with($amostra);

        $useCase = new RejeitarAmostra($repository);

        $resultado = $useCase->executar($amostra);

        $this->assertTrue($resultado);
        $this->assertSame('Rejeitada', $amostra->obterStatus());
    }
}

?>
