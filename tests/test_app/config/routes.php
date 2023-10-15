<?php

use Cake\Routing\RouteBuilder;

/** @var \Cake\Routing\RouteBuilder $routes */
$routes->prefix('Admin', function (RouteBuilder $builder) {
    return $builder->fallbacks();
});

$routes->scope('/', function (RouteBuilder $builder) {
    $builder->fallbacks();
});
