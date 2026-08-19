<?php

namespace Tests\Application\UserCases\IniciarAnalise;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\IniciarAnalise\IniciarAnalise;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;
use Exception;

class IniciarAnaliseTest extends TestCase
{
    public function testDeveIniciarAnalise(): void
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

        $useCase = new IniciarAnalise($repository);

        $resultado = $useCase->executar($amostra);

        $this->assertTrue($resultado);
        $this->assertSame('EmAnalise', $amostra->obterStatus());
    }

    public function testNaoDeveIniciarAnaliseSemResponsavel(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->never())
            ->method('atualizar');

        $amostra = new Amostra(
            tipo: 'Água',
            responsavel_tecnico: null,
            data_recebimento: '2026-08-18'
        );

        $amostra->definirId(1);

        $useCase = new IniciarAnalise($repository);

        $this->expectException(Exception::class);

        $useCase->executar($amostra);
    }
}


?>
