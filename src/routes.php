<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});

$app->get('/search', '\TopPack\controllers\SearchController:getRepos' );

$app->get('/top-packages', '\TopPack\controllers\PackageController:topPackages' );

//$search_object = new \TopPack\services\ReposSearchService;
//$search->import();

$app->post('/import', '\TopPack\controllers\SearchController:import');
