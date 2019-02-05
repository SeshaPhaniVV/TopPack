<?php

namespace TopPack\services;

use \GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception;

class GithubService{

    public function getRepositories($query, $sort_term, $order, $c){
        $client = new Client();
        $request_uri = "https://api.github.com/search/repositories?q=${query}&sort=${sort_term}&order=${order}";
        $response = $client->request('GET', $request_uri);

        $logger = $c->get('logger');

        $logger->debug( $response->getBody());

        return $response->getBody();
    }

    public function readPackageJson($ownername, $reponame, $c) {
        $request_uri = "https://api.github.com/repos/${ownername}/${reponame}/contents/package.json";
        $client = new Client();
        $dependencies = [];
        $logger = $c->get('logger');
        try {
            $response = $client->request('GET', $request_uri);
            $contents = json_decode($response->getBody(), true);
            $logger->info($contents);
            $response = base64_decode($contents['content']);
            $dependencies = json_decode($response, true);
            $logger->warning($dependencies);
        } catch (Exception\RequestException $e) {
            $logger->error(Psr7\str($e->getRequest()));
            if ($e->hasResponse()) {
                $logger->error(Psr7\str($e->getResponse()));
            }
        }
        catch (Exception\TransferException $e) {
            $logger->error(Psr7\str($e->getRequest()));
            $logger->error(Psr7\str($e->getResponse()));
        }

        return $dependencies;
    }
}