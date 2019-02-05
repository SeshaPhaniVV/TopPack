<?php

namespace TopPack\models;
use \Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Capsule\Manager as DB;

use Carbon\Carbon;

class Package extends Model {

    protected $fillable = ['name'];

    public function repositories() {
        return $this->belongsToMany('TopPack\models\Repository', "repository_packages", "package_id", "repository_id");
    }

    public static function getTopPackages() {
        return DB::table((new static)->getTable());
    }

    public static function importPackages(array $packageNames, $c) {

        $logger = $c->get('logger');

        $existingPackages = Package::whereIn('name', $packageNames)->pluck('name')->toArray();

        $packages=array_diff($packageNames,$existingPackages);

        $logger->debug("PPPPPACKAGES :: {$packages}");

        $now = Carbon::now('utc')->toDateTimeString();

        $data = [];
        foreach($packages as $package) {
            array_push($data, array('name' => $package, 'created_at'=> $now, 'updated_at'=> $now));
        }

        Package::insert($data);
        return Package::whereIn("name", $packageNames)->get();
    }
}