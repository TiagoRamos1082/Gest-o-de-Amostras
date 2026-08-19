<?php

namespace Tests\Application\UserCases\AtualizarTipoAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\AtualizarTipoAmostra\AtualizarTipoAmostra;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class AtualizarTipoAmostraTest extends TestCase
{
    public function testDeveAtualizarTipoDaAmostra(): void
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

        $useCase = new AtualizarTipoAmostra($repository);

        $resultado = $useCase->executar(
            $amostra,
            'Solo'
        );

        $this->assertTrue($resultado);
        $this->assertSame('Solo', $amostra->obterTipo());
    }
}

?>
