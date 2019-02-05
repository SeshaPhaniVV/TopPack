<?php

namespace TopPack\models;

use \Illuminate\Database\Eloquent\Model;

class RepositoryPackage extends Model {
    protected $fillable = [
        "repository_id",
        "package_id"
    ];

    /**
     * @var string
     */
    protected $table = 'repository_packages';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages() {
        return $this->belongsToMany('TopPack\models\Package', "packages", "package_id");
    }

    public function repositories() {
        return $this->belongsToMany('TopPack\models\Repository', "repositories", "repository_id");
    }
}