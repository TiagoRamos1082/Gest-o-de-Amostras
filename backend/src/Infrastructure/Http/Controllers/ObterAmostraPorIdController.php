<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\ObterPorId\ObterPorId;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ObterAmostraPorIdController
{
    private ObterPorId $obterPorId;

    public function __construct(ObterPorId $obterPorId)
    {
        $this->obterPorId = $obterPorId;
    }

    public function executar(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $id = (int) $args['id'];

        $amostra = $this->obterPorId->executar($id);

        if ($amostra === false) {
            $response->getBody()->write(
                json_encode([
                    'mensagem' => 'Amostra nao encontrada.'
                ])
            );

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(404);
        }

        $response->getBody()->write(
            json_encode($amostra)
        );

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}
