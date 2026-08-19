<?php

namespace Tests\Application\UserCases\AtualizarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Domain\Amostra;
use App\Application\UserCases\AtualizarAmostra\AtualizarAmostra;
use PHPUnit\Framework\TestCase;

class AtualizarAmostraTest extends TestCase
{
    public function testDeveAtualizarAmostra(): void
    {
        $repository = $this->createMock(AmostraRepository::class);

        $amostra = new Amostra(
            'Água',
            'João',
            '2026-08-18'
        );

        $amostra->definirId(1);

        $repository
            ->expects($this->once())
            ->method('atualizar')
            ->with($amostra);

        $useCase = new AtualizarAmostra($repository);

        $resultado = $useCase->executar($amostra);

        $this->assertTrue($resultado);
    }
}

?>
