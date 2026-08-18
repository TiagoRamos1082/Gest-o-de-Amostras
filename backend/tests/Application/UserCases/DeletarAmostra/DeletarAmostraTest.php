<?php

namespace Tests\Application\UserCases\DeletarAmostra;

use App\Application\Repositories\AmostraRepository;
use App\Application\UserCases\DeletarAmostra\DeletarAmostra;
use App\Domain\Amostra;
use PHPUnit\Framework\TestCase;

class DeletarAmostraTest extends TestCase
{
    public function testDeletarAmostra(): void
    {
        $amostra = new Amostra(
            'Agua',
            'Joao',
            '2026-03-22'
        );

        $amostra->definirId(39);

        $repository = $this->createMock(AmostraRepository::class);

        $repository
            ->expects($this->once())
            ->method('deletar')
            ->with(39);

        $deletarAmostra = new DeletarAmostra($repository);

        $resultado = $deletarAmostra->executar($amostra);

        $this->assertTrue($resultado);
    }
}

?>
