<?php
require_once __DIR__ . ("/vendor/autoload.php");
require_once __DIR__.("/class.php");
$parse = new Parse;
$read = new Read;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App();
$app->get('/post/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    //На этом моменте проблема, по коду метод View должен вернуть массив $a, в котором запись с ID из того, что придёт из GET а возвращает объект состоящий из нескольких элементов и пустого массива.
    $view = new View($id);
        $response->getBody()->write("Article, $view");
        return $response;

});
$app->run();
?>