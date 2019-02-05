<?php

use Illuminate\Database\Capsule\Manager as Capsule;

// load composer autoload
require __DIR__ .'/vendor/autoload.php';

$capsule = new Capsule();

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'orm_test',
    'username'  => 'root',
    'password'  => 'phanisesha575',
]);

$capsule->setAsGlobal();

$capsule->bootEloquent();

// create table
Capsule::schema()->create('repositories', function ($table) {
    $table->increments('id');
    $table->integer('repository_id');
    $table->unique('repository_id');
    $table->string('description');
    $table->string('owner_name');
    $table->string('repo_name');
    $table->integer('star_count');
    $table->integer('fork_count');
    $table->string('repo_url');
    $table->timestamps();
});

Capsule::schema()->create('packages', function ($table) {
    $table->increments('id');
    $table->string('name');
    $table->unique('name');
    $table->timestamps();
});

Capsule::schema()->create('repository_packages', function ($table) {
    $table->increments('id');
    $table->integer('repository_id')->unsigned();
    $table->integer('package_id')->unsigned();
    $table->foreign('repository_id')->references('id')->on('repositories');
    $table->foreign('package_id')->references('id')->on('packages');
});

echo 'Table created successfully!';