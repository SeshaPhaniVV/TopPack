<?php

namespace TopPack\Controllers;

use TopPack\models\Package;
use \TopPack\models\RepositoryPackage;

class PackageController {

    protected $logger;

    protected $container;

    protected $db;

    public function __construct($container)
    {
        $this->container = $container;
        $this->db = $container->get('database');
    }

    public function topPackages($request, $response) {
        $package_ids = RepositoryPackage::groupBy('package_id')->selectRaw('count(*) as total, package_id')->get()->sortByDesc("total")->take(10)->values()->pluck('package_id');
        $logger  = $this->container->get('logger');

        $logger->error(json_encode($package_ids));

        $packages = Package::whereIn('id', $package_ids)->get();

        $logger->debug(json_encode($packages));

        $response->getBody()->write(json_encode($packages));
        return $response;
    }
}