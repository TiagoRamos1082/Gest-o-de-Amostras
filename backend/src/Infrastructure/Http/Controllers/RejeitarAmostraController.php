<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\ObterAmostra\ObterAmostra;
use App\Application\UserCases\RejeitarAmostra\RejeitarAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RejeitarAmostraController
{
    private RejeitarAmostra $rejeitarAmostra;
    private ObterAmostra $obterAmostra;

    public function __construct(
        RejeitarAmostra $rejeitarAmostra,
        ObterAmostra $obterAmostra
    ) {
        $this->rejeitarAmostra = $rejeitarAmostra;
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

        try {
            $this->rejeitarAmostra->executar($amostra);

            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Amostra rejeitada com sucesso.'
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
