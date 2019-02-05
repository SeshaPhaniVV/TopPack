<?php

namespace TopPack\models;

use \Illuminate\Database\Eloquent\Model;

class Repository extends Model {
    protected $fillable = [
        "repository_id",
        "description",
        "owner_name",
        "repo_name",
        "star_count",
        "fork_count",
        "repo_url"
    ];

    /**
     * @var string
     */
    protected $table = 'repositories';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function packages() {
        return $this->belongsToMany('TopPack\models\Package', "repository_packages", "repository_id", "package_id");
    }
}