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
Capsule::schema()->create('tasks', function ($table) {
$table->increments('id');
$table->string('title');
$table->string('body');
$table->timestamps();
});

echo 'Table created successfully!';