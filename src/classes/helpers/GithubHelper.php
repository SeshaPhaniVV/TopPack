<?php

namespace TopPack\helpers;

class GithubHelper {

    public function getRepository($request, $c){

        $logger = $c->get('logger');

        var_dump($request->getParsedBody());

        $repository = $request->getParsedBody();

        $logger->warning($repository);

        $data = [
            'owner_name' => $repository['owner_name'],
            'repository_id' => $repository['repository_id'],
            'description' => $repository['description'],
            'repo_name' => $repository['repo_name'],
            'star_count' => $repository['star_count'],
            'fork_count' => $repository['fork_count'],
            'repo_url' => $repository['repo_url']
        ];
        return $data;
    }

    public function getPackages($packages, $c){
        $logger = $c->get('logger');
        $logger->warning($packages);
        $dependencies = [];
        $devDependencies = [];
        if( sizeof($packages) > 0 ) {
            if (isset($packages['devDependencies'])) {
                $devDependencies = array_keys($packages['devDependencies'] ?: []);
            };
            if (isset($packages['dependencies'])) {
                $dependencies = array_keys($packages['dependencies'] ?: []);
            };
            $mergedPackages = array_unique(array_merge($dependencies, $devDependencies));
            return $mergedPackages;
        }
        return [];
        }
}

