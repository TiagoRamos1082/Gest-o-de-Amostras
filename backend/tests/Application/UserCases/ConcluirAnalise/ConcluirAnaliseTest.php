<?php

namespace Tests\Application\UserCases\ConcluirAnalise;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\ConcluirAnalise\ConcluirAnalise;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class ConcluirAnaliseTest extends TestCase
{
    public function testDeveConcluirAnalise(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $amostra = new Amostra(
            tipo: 'Água',
            responsavel_tecnico: 'João',
            data_recebimento: '2026-08-18'
        );

        $amostra->definirId(1);

        // Primeiro coloca a amostra em análise
        $amostra->iniciarAnalise();

        $repository
            ->expects($this->once())
            ->method('atualizar')
            ->with($amostra);

        $useCase = new ConcluirAnalise($repository);

        $resultado = $useCase->executar(
            $amostra,
            '2026-08-19'
        );

        $this->assertTrue($resultado);
        $this->assertSame('Concluida', $amostra->obterStatus());
        $this->assertSame(
            '2026-08-19',
            $amostra->obterDataConclusao()->format('Y-m-d')
        );
    }

    public function testNaoDeveConcluirComDataAnteriorAoRecebimento(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->never())
            ->method('atualizar');

        $amostra = new Amostra(
            tipo: 'Água',
            responsavel_tecnico: 'João',
            data_recebimento: '2026-08-18'
        );

        $amostra->definirId(1);
        $amostra->iniciarAnalise();

        $useCase = new ConcluirAnalise($repository);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage(
            'Data de conclusão deve ser posterior à data de recebimento.'
        );

        $useCase->executar(
            $amostra,
            '2026-08-17'
        );
    }
}
