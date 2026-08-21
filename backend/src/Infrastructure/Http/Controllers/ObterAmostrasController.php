<?php

namespace App\Infrastructure\Http\Controllers;

use App\Application\UserCases\ObterAmostras\ObterAmostras;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ObterAmostrasController
{
    private ObterAmostras $obterAmostras;

    public function __construct(ObterAmostras $obterAmostras)
    {
        $this->obterAmostras = $obterAmostras;
    }

    public function executar(
        Request $request,
        Response $response
    ): Response {

        $amostras = $this->obterAmostras->executar();

        $response->getBody()->write(
            json_encode($amostras)
        );

        return $response->withHeader(
            'Content-Type',
            'application/json'
        );
    }
}
