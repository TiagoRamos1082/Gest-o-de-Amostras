<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\AtualizarTipoAmostra\AtualizarTipoAmostra;
use App\Application\UserCases\ObterAmostra\ObterAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AtualizarTipoAmostraController
{
    private AtualizarTipoAmostra $atualizarTipoAmostra;
    private ObterAmostra $obterAmostra;

    public function __construct(
        AtualizarTipoAmostra $atualizarTipoAmostra,
        ObterAmostra $obterAmostra
    ) {
        $this->atualizarTipoAmostra = $atualizarTipoAmostra;
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
            $this->atualizarTipoAmostra->executar(
                $amostra,
                $dados['tipo']
            );

            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Tipo da amostra atualizado com sucesso.'
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
