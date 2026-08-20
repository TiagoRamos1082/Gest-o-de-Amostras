<?php

namespace Tests\Application\UserCases\ObterAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\ObterAmostra\ObterAmostra;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class ObterAmostraTest extends TestCase
{
    public function testDeveObterAmostra(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $amostra = new Amostra(
            tipo: 'Água',
            responsavel_tecnico: 'João',
            data_recebimento: '2026-08-18'
        );

        $amostra->definirId(67);

        $repository
            ->expects($this->once())
            ->method('obterAmostraPorId')
            ->with(67)
            ->willReturn($amostra);

        $useCase = new ObterAmostra($repository);

        $resultado = $useCase->executar(67);

        $this->assertSame($amostra, $resultado);
    }

    public function testDeveRetornarFalseQuandoAmostraNaoExistir(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->once())
            ->method('obterAmostraPorId')
            ->with(999)
            ->willReturn(false);

        $useCase = new ObterAmostra($repository);

        $resultado = $useCase->executar(999);

        $this->assertFalse($resultado);
    }
}
