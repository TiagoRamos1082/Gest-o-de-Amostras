<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\IniciarAnalise\IniciarAnalise;
use App\Application\UserCases\ObterAmostra\ObterAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IniciarAnaliseController
{
    private IniciarAnalise $iniciarAnalise;
    private ObterAmostra $obterAmostra;

    public function __construct(
        IniciarAnalise $iniciarAnalise,
        ObterAmostra $obterAmostra
    ) {
        $this->iniciarAnalise = $iniciarAnalise;
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
            $this->iniciarAnalise->executar($amostra);

            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Análise iniciada com sucesso.'
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
