<?php
require_once ('C:\OS\OSPanel\domains\localhost\OOP\vendor\autoload.php');
require_once ('C:\OS\OSPanel\domains\localhost\OOP\model.php');
require_once ('C:\OS\OSPanel\domains\localhost\OOP\controller.php');
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
$app = new \Slim\App();
$app->get('/post/{id}', function (Request $request, Response $response) {
    $id = $request->getAttribute('id');
    $classView = new View($id);
    $massive = $classView -> View($id);
    $massive_separate = implode (" : ",$massive);
    $response -> getBody()->write("$massive_separate");
    return $response;
});
$app->run();
?>