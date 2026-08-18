<?php
namespace Tests\Domain;

use App\Domain\Amostra;
use Exception;
use PHPUnit\Framework\TestCase;

class AmostraTest extends TestCase
{
    // ---------- Construção / status inicial ----------

    public function testAmostraComecaComoRecebida(): void
    {
        $amostra = new Amostra(
            "Agua",
            null,
            "2026-08-13"
        );
        $this->assertSame("Recebida", $amostra->obterStatus());
    }

    // ---------- iniciarAnalise ----------

    public function testNaoPodeIniciarAnaliseSemResponsavel(): void
    {
        $amostra = new Amostra(
            "Agua",
            null,
            "2026-08-13"
        );
        $this->expectException(Exception::class);
        $amostra->iniciarAnalise();
    }

    public function testPodeIniciarAnaliseComResponsavel(): void
    {
        $amostra = new Amostra(
            "Agua",
            "Joao",
            "2026-08-13"
        );
        $amostra->iniciarAnalise();
        $this->assertSame("EmAnalise", $amostra->obterStatus());
    }

    public function testNaoPodeIniciarAnaliseSeJaEstiverEmAnalise(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();

        $this->expectException(Exception::class);
        $amostra->iniciarAnalise();
    }

    public function testNaoPodeIniciarAnaliseSeAmostraJaFinalizada(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->expectException(Exception::class);
        $amostra->iniciarAnalise();
    }

    // ---------- concluirAnalise ----------

    public function testPodeConcluirAnaliseComDadosValidos(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->assertSame("Concluida", $amostra->obterStatus());
    }

    public function testNaoPodeConcluirAnaliseSeNaoEstiverEmAnalise(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");

        $this->expectException(Exception::class);
        $amostra->concluirAnalise("2026-08-14");
    }

    public function testNaoPodeConcluirAnaliseComDataVazia(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();

        $this->expectException(Exception::class);
        $amostra->concluirAnalise("");
    }

    public function testNaoPodeConcluirAnaliseComDataInvalida(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();

        $this->expectException(Exception::class);
        $amostra->concluirAnalise("data-que-nao-existe");
    }

    public function testNaoPodeConcluirAnaliseComDataAnteriorAoRecebimento(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();

        $this->expectException(Exception::class);
        $amostra->concluirAnalise("2026-08-01");
    }

    public function testNaoPodeConcluirAnaliseDeAmostraJaFinalizada(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->expectException(Exception::class);
        $amostra->concluirAnalise("2026-08-15");
    }

    // ---------- rejeitarAmostra ----------

    public function testPodeRejeitarAmostraRecemRecebida(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->rejeitarAmostra();

        $this->assertSame("Rejeitada", $amostra->obterStatus());
    }

    public function testPodeRejeitarAmostraEmAnalise(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->rejeitarAmostra();

        $this->assertSame("Rejeitada", $amostra->obterStatus());
    }

    public function testNaoPodeRejeitarAmostraJaConcluida(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->expectException(Exception::class);
        $amostra->rejeitarAmostra();
    }

    public function testNaoPodeRejeitarAmostraJaRejeitada(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->rejeitarAmostra();

        $this->expectException(Exception::class);
        $amostra->rejeitarAmostra();
    }

    // ---------- definirId ----------

    public function testDefinirIdComValorValido(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirId(1);

        $this->assertTrue(true);
    }

    public function testNaoPodeDefinirIdDuasVezes(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirId(1);

        $this->expectException(Exception::class);
        $amostra->definirId(2);
    }

    public function testNaoPodeDefinirIdZeroOuNegativo(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");

        $this->expectException(Exception::class);
        $amostra->definirId(0);
    }

    // ---------- definirCodigo ----------

    public function testDefinirCodigoComSequencialValido(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirCodigo(1);

        $this->assertTrue(true);
    }

    public function testNaoPodeDefinirCodigoDuasVezes(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirCodigo(1);


        $this->expectException(Exception::class);
        $amostra->definirCodigo(2);
    }

    public function testNaoPodeDefinirCodigoComSequencialZeroOuNegativo(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");

        $this->expectException(Exception::class);
        $amostra->definirCodigo(0);
    }

    // ---------- definirTipo ----------

    public function testPodeDefinirTipoValido(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirTipo("Solo");

        $this->assertTrue(true);
    }

    public function testNaoPodeDefinirTipoVazio(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");

        $this->expectException(Exception::class);
        $amostra->definirTipo("");
    }

    public function testNaoPodeDefinirTipoDeAmostraFinalizada(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->expectException(Exception::class);
        $amostra->definirTipo("Solo");
    }

    // ---------- definirTecnico ----------

    public function testPodeDefinirTecnico(): void
    {
        $amostra = new Amostra("Agua", null, "2026-08-13");
        $amostra->definirTecnico("Maria");

        $amostra->iniciarAnalise();
        $this->assertSame("EmAnalise", $amostra->obterStatus());
    }

    public function testPodeDefinirTecnicoComoNulo(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->definirTecnico(null);

        $this->expectException(Exception::class);
        $amostra->iniciarAnalise();
    }

    public function testNaoPodeDefinirTecnicoDeAmostraFinalizada(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");
        $amostra->iniciarAnalise();
        $amostra->concluirAnalise("2026-08-14");

        $this->expectException(Exception::class);
        $amostra->definirTecnico("Maria");
    }

    public function testRetornoDoAnoDaDataDeRecebimento(): void
    {
        $amostra = new Amostra("Agua", "Joao", "2026-08-13");

        $this->assertSame(2026, $amostra->obterAnoDataRecebimento());
    }
}
