<?php

namespace App\Domain;

use DateTime;
use Exception;

class Amostra
{
    private ?int $id;
    private ?string $codigo;
    private string $tipo;
    private string $status;
    private ?string $responsavel_tecnico;
    private DateTime $data_recebimento;
    private ?DateTime $data_conclusao;
    private bool $bloqueada;

    public function __construct(
        string $tipo,
        ?string $responsavel_tecnico,
        string $data_recebimento,
    ) {
        $this->id = null;
        $this->codigo = null;
        $this->tipo = $tipo;
        $this->status = "Recebida";
        $this->responsavel_tecnico = $responsavel_tecnico;
        $this->data_recebimento = new DateTime($data_recebimento);
        $this->data_conclusao = null;
        $this->bloqueada = false;
    }

    public static function reconstruir(
        int $id,
        string $codigo,
        string $tipo,
        string $status,
        ?string $responsavelTecnico,
        string $dataRecebimento,
        ?string $dataConclusao,
        bool $bloqueada
    ): self {
        $amostra = new self(
            $tipo,
            $responsavelTecnico,
            $dataRecebimento
        );

        $amostra->id = $id;
        $amostra->codigo = $codigo;
        $amostra->status = $status;
        $amostra->data_conclusao = $dataConclusao !== null
            ? new DateTime($dataConclusao)
            : null;
        $amostra->bloqueada = $bloqueada;

        return $amostra;
    }

    private function amostraBloqueada(): bool
    {
        return $this->bloqueada;
    }

    public function estaBloqueada(): bool
    {
        return $this->bloqueada;
    }

    private function definirBloqueada(): void
    {
        $this->bloqueada = true;
    }

    public function definirId(int $id): void
    {
        if ($this->id !== null) {
            throw new Exception("ID já definido.");
        }
        if ($id <= 0) {
            throw new Exception("ID inválido.");
        }
        $this->id = $id;
    }

    public function obterId(): int {
        return $this->id;
    }

    public function definirCodigo(int $sequencial): void
    {
        if ($sequencial <= 0) {
            throw new Exception("Sequencial inválido.");
        }
        if ($this->codigo !== null) {
            throw new Exception("Código já definido.");
        }
        $this->codigo = $this->criarCodigoAmostra($sequencial);
    }

    public function obterCodigo(): ?string
    {
        return $this->codigo;
    }

    public function definirTipo(string $tipo): void
    {
        if ($this->amostraBloqueada()) {
            throw new Exception("Amostra já finalizada.");
        }
        if (empty($tipo)) {
            throw new Exception("Informe um tipo válido.");
        }
        $this->tipo = $tipo;
    }

    public function obterTipo(): string
    {
        return $this->tipo;
    }

    public function obterStatus(): string
    {
        return $this->status;
    }

    public function obterResponsavelTecnico(): ?string
    {
        return $this->responsavel_tecnico;
    }

    public function definirTecnico(?string $tecnico): void
    {
        if ($this->amostraBloqueada()) {
            throw new Exception("Amostra já finalizada.");
        }
        $this->responsavel_tecnico = $tecnico;
    }

    public function obterDataRecebimento(): DateTime
    {
        return $this->data_recebimento;
    }

    public function obterDataConclusao(): ?DateTime
    {
        return $this->data_conclusao;
    }

    public function obterAnoDataRecebimento(): int
    {
        return (int) $this->data_recebimento->format('Y');
    }

    private function criarCodigoAmostra(int $sequencial): string
    {
        $prefixo = "CAND108";
        $ano = $this->data_recebimento->format('Y');
        return sprintf("%s-%s-%06d", $prefixo, $ano, $sequencial);
    }

    public function iniciarAnalise(): void
    {
        if ($this->amostraBloqueada()) {
            throw new Exception("Amostra já finalizada.");
        }
        if ($this->status !== "Recebida") {
            throw new Exception("Amostra não pode ser colocada em análise.");
        }
        if (empty($this->responsavel_tecnico)) {
            throw new Exception("Responsável técnico é obrigatório.");
        }
        $this->status = "EmAnalise";
    }

    public function concluirAnalise(string $data_conclusao): void
    {
        if ($this->amostraBloqueada()) {
            throw new Exception("Amostra já finalizada.");
        }
        if ($this->status !== "EmAnalise") {
            throw new Exception("Amostra não está em análise.");
        }
        if (empty($data_conclusao)) {
            throw new Exception("Data de conclusão é obrigatória.");
        }
        try {
            $dataConclusao = new DateTime($data_conclusao);
            $dataRecebimento = $this->data_recebimento;
        } catch (Exception $exception) {
            throw new Exception("Data de conclusão inválida.");
        }
        if ($dataConclusao < $dataRecebimento) {
            throw new Exception("Data de conclusão deve ser posterior à data de recebimento.");
        }
        $this->data_conclusao = $dataConclusao;
        $this->status = "Concluida";
        $this->definirBloqueada();
    }

    public function rejeitarAmostra(): void
    {
        if ($this->amostraBloqueada()) {
            throw new Exception("Amostra já finalizada.");
        }
        if ($this->status === "Concluida") {
            throw new Exception("Não é possível rejeitar uma amostra já concluída.");
        }
        $this->status = "Rejeitada";
        $this->definirBloqueada();
    }
}

?>
