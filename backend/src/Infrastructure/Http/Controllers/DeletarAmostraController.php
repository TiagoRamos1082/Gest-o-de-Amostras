<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\DeletarAmostra\DeletarAmostra;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DeletarAmostraController
{
    private DeletarAmostra $deletarAmostra;

    public function __construct(DeletarAmostra $deletarAmostra)
    {
        $this->deletarAmostra = $deletarAmostra;
    }

    public function executar(
        Request $request,
        Response $response,
        array $args
    ): Response {
        $id = (int) $args['id'];

        $this->deletarAmostra->executar($id);

        return $response->withStatus(204);
    }
}
