<?php

use App\Application\UserCases\AtualizarResponsavelTecnico\AtualizarResponsavelTecnico;
use App\Application\UserCases\AtualizarTipoAmostra\AtualizarTipoAmostra;
use App\Application\UserCases\ConcluirAnalise\ConcluirAnalise;
use App\Application\UserCases\CriarAmostra\CriarAmostra;
use App\Application\UserCases\DeletarAmostra\DeletarAmostra;
use App\Application\UserCases\IniciarAnalise\IniciarAnalise;
use App\Application\UserCases\ObterAmostra\ObterAmostra;
use App\Application\UserCases\ObterPorId\ObterPorId;
use App\Application\UserCases\RejeitarAmostra\RejeitarAmostra;

use App\Infrastructure\DataBase\Connection;
use App\Infrastructure\Http\Controllers\AtualizarResponsavelTecnicoController;
use App\Infrastructure\Http\Controllers\AtualizarTipoAmostraController;
use App\Infrastructure\Http\Controllers\ConcluirAnaliseController;
use App\Infrastructure\Http\Controllers\CriarAmostraController;
use App\Infrastructure\Http\Controllers\DeletarAmostraController;
use App\Infrastructure\Http\Controllers\IniciarAnaliseController;
use App\Infrastructure\Http\Controllers\ObterAmostraPorIdController;
use App\Infrastructure\Http\Controllers\RejeitarAmostraController;
use App\Infrastructure\Repositories\AmostraRepository;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$app = AppFactory::create();

$app->addBodyParsingMiddleware();

$connection = Connection::create();
$repository = new AmostraRepository($connection);


/*
 * Use Cases
 */

$criarAmostra = new CriarAmostra($repository);
$obterAmostraPorId = new ObterPorId($repository);
$obterAmostra = new ObterAmostra($repository);
$deletarAmostra = new DeletarAmostra($repository);
$atualizarTipoAmostra = new AtualizarTipoAmostra($repository);
$atualizarResponsavelTecnico = new AtualizarResponsavelTecnico($repository);
$iniciarAnalise = new IniciarAnalise($repository);
$concluirAnalise = new ConcluirAnalise($repository);
$rejeitarAmostra = new RejeitarAmostra($repository);


/*
 * Controllers
 */

$criarAmostraController = new CriarAmostraController(
    $criarAmostra
);

$obterAmostraPorIdController = new ObterAmostraPorIdController(
    $obterAmostraPorId
);

$deletarAmostraController = new DeletarAmostraController(
    $deletarAmostra
);

$atualizarTipoAmostraController = new AtualizarTipoAmostraController(
    $atualizarTipoAmostra,
    $obterAmostra
);

$atualizarResponsavelTecnicoController = new AtualizarResponsavelTecnicoController(
    $atualizarResponsavelTecnico,
    $obterAmostra
);

$iniciarAnaliseController = new IniciarAnaliseController(
    $iniciarAnalise,
    $obterAmostra
);

$concluirAnaliseController = new ConcluirAnaliseController(
    $concluirAnalise,
    $obterAmostra
);

$rejeitarAmostraController = new RejeitarAmostraController(
    $rejeitarAmostra,
    $obterAmostra
);


/*
 * Rotas
 */

$app->post(
    '/amostras',
    [$criarAmostraController, 'executar']
);

$app->get(
    '/amostras/{id}',
    [$obterAmostraPorIdController, 'executar']
);

$app->delete(
    '/amostras/{id}',
    [$deletarAmostraController, 'executar']
);

$app->put(
    '/amostras/{id}/tipo',
    [$atualizarTipoAmostraController, 'executar']
);

$app->put(
    '/amostras/{id}/responsavel',
    [$atualizarResponsavelTecnicoController, 'executar']
);

$app->post(
    '/amostras/{id}/iniciar-analise',
    [$iniciarAnaliseController, 'executar']
);

$app->post(
    '/amostras/{id}/concluir-analise',
    [$concluirAnaliseController, 'executar']
);

$app->post(
    '/amostras/{id}/rejeitar',
    [$rejeitarAmostraController, 'executar']
);


/*
 * Rota de teste
 */

$app->get('/teste', function (
    Request $request,
    Response $response
) {
    $response->getBody()->write(
        json_encode([
            'mensagem' => 'API funcionando!'
        ])
    );

    return $response->withHeader(
        'Content-Type',
        'application/json'
    );
});


$app->run();
