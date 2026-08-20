<?php

namespace Tests\Application\UserCases\CriarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\CriarAmostra\CriarAmostra;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class CriarAmostraTest extends TestCase
{
    public function testCriarAmostra(): void
    {
        $dados = [
            'tipo' => 'Agua',
            'responsavelTecnico' => 'Joao',
            'dataRecebimento' => '2026-03-22',
        ];

        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->once())
            ->method('obterProximoSequencial')
            ->with(2026)
            ->willReturn(1);

        $repository
            ->expects($this->once())
            ->method('salvar')
            ->with($this->isInstanceOf(Amostra::class));

        $criarAmostra = new CriarAmostra($repository);

        $amostra = $criarAmostra->executar($dados);

        $this->assertInstanceOf(Amostra::class, $amostra);
        $this->assertSame('Agua', $amostra->obterTipo());
        $this->assertSame('Joao', $amostra->obterResponsavelTecnico());
        $this->assertSame(
            '2026-03-22',
            $amostra->obterDataRecebimento()->format('Y-m-d')
        );
    }
}
