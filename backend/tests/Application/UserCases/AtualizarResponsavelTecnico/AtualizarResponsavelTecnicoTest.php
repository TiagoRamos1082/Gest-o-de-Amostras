<?php

namespace Tests\Application\UserCases\AtualizarResponsavelTecnico;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\AtualizarResponsavelTecnico\AtualizarResponsavelTecnico;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class AtualizarResponsavelTecnicoTest extends TestCase
{
    public function testDeveAtualizarResponsavelTecnico(): void
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

        $useCase = new AtualizarResponsavelTecnico($repository);

        $resultado = $useCase->executar(
            $amostra,
            'Carlos'
        );

        $this->assertTrue($resultado);
        $this->assertSame(
            'Carlos',
            $amostra->obterResponsavelTecnico()
        );
    }
}

?>
