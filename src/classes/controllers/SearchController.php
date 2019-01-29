<?php
namespace TopPack\Controllers;

class SearchController
{
    protected $container;

    //this constructor passes the DIC in so we can get our PenFactory out of it later
    function __construct($container)
    {
        $this->container = $container;
    }

    function getRepos($request, $response, $args)
    {
        $search_term = $request->getQueryParams();
        $query = $search_term['q'];
        $sort_term = $search_term['sort'];
        $order = $search_term['order'];

        $githubService = new \TopPack\services\GithubService;

        $repositories = json_decode((string) $githubService->getRepositories($query, $sort_term, $order, $this->container), true)['items'];

//        $repositories = $githubService->getRepositories("", "", "", $this->container);

        $logger = $this->container->get('logger');

        $logger->debug($repositories);

//        var_dump($repositories);


        $args['response'] = $repositories;

        return $response->withJson($repositories);
//        return $repositories;
//        return $this->container->get('renderer')->render($response, 'index.phtml', $args);
    }

    function import($ownername, $reponame, $c) {
        $githubService = new \TopPack\services\GithubService;

        $githubService->readPackageJson($ownername, $reponame, $c);
//        return $response->withJson(array("a"=>"b", "c"=>"d"));


    }
}
