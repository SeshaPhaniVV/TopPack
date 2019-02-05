<?php
namespace TopPack\Controllers;

use \TopPack\models\Repository;

use \TopPack\models\Package;

class SearchController
{
    protected $container;

    /**
     *
     */
    protected $db;

    //this constructor passes the DIC
    function __construct($container)
    {
        $this->container = $container;
        $this->db = $container->get('database');
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

        $logger->debug("aaaaaaaaaaaaaaaaaaaaaaaa");

        $logger->debug(count($repositories));

        $args['response'] = $repositories;

        return $response->withJson($repositories);
//        return $repositories;
//        return $this->container->get('renderer')->render($response, 'index.phtml', $args);
    }

    function import($request, $response, $args) {
        $logger = $this->container->get('logger');

        $githubService = new \TopPack\services\GithubService;

        $githubHelper = new \TopPack\helpers\GithubHelper;

        $repository = new Repository($githubHelper->getRepository($request, $this->container));

        $logger->debug($repository);

        $repoId = $repository['repository_id'];

        $isImported = false;

        if (Repository::where("repository_id", $repoId)->exists()) {
            $isImported = true;
            $logger->warning("Repository already exists");
        }

        $raw_packages = $githubService->readPackageJson($repository['owner_name'], $repository['repo_name'], $this->container);

        $packages = $githubHelper->getPackages($raw_packages, $this->container);

        try {
            $this->db->getConnection()->transaction(function () use ($repository, $packages, $logger, $isImported) {
                $logger->debug("Repository :: {$repository}");
                $logger->debug("Packages :: {$packages}");
                if ($isImported) {
                    $logger->error("Unable to save the repository");
                } else {
                    $repository->save();
                    $importedPackages = Package::importPackages($packages, $this->container);
                    $logger->info($importedPackages);
                    $repository->packages()->attach($importedPackages);
                }
            });
        } catch (\Exception $e) {
            $logger->error("Something went wrong");
        }
        $logger->info("final Return:: {$packages}");
        return $packages;
    }
}
