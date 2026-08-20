<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\CriarAmostra\CriarAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CriarAmostraController
{
    private CriarAmostra $criarAmostra;

    public function __construct(CriarAmostra $criarAmostra)
    {
        $this->criarAmostra = $criarAmostra;
    }

    public function executar(Request $request, Response $response): Response
    {
        $dados = $request->getParsedBody();

        $amostra = $this->criarAmostra->executar($dados);

        $response->getBody()->write(
            json_encode([
                'id' => $amostra->obterId(),
                'codigo' => $amostra->obterCodigo(),
                'tipo' => $amostra->obterTipo(),
                'status' => $amostra->obterStatus(),
                'responsavelTecnico' => $amostra->obterResponsavelTecnico(),
                'dataRecebimento' => $amostra->obterDataRecebimento()->format('Y-m-d')
            ])
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201);
    }
}

?>
