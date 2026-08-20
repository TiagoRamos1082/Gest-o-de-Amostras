<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\AtualizarResponsavelTecnico\AtualizarResponsavelTecnico;
use App\Application\UserCases\ObterAmostra\ObterAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AtualizarResponsavelTecnicoController
{
    private AtualizarResponsavelTecnico $atualizarResponsavelTecnico;
    private ObterAmostra $obterAmostra;

    public function __construct(
        AtualizarResponsavelTecnico $atualizarResponsavelTecnico,
        ObterAmostra $obterAmostra
    ) {
        $this->atualizarResponsavelTecnico = $atualizarResponsavelTecnico;
        $this->obterAmostra = $obterAmostra;
    }

    public function executar(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $id = (int) $args['id'];

        $amostra = $this->obterAmostra->executar($id);

        if ($amostra === false) {
            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Amostra não encontrada.'
                ])
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $dados = $request->getParsedBody();

        try {
            $this->atualizarResponsavelTecnico->executar(
                $amostra,
                $dados['responsavelTecnico'] ?? null
            );

            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Responsável técnico atualizado com sucesso.'
                ])
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);

        } catch (\Exception $exception) {
            $response->getBody()->write(
                json_encode([
                    'mensagem' => $exception->getMessage()
                ])
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }
}
