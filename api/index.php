<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Nyholm\Psr7Server\ServerRequestCreator::createFromGlobals();

$response = $kernel->handle(
    Illuminate\Http\Request::createFromBase(
        Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory::createRequest($request)
    )
);

$response->send();

$kernel->terminate(
    Illuminate\Http\Request::createFromBase(
        Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory::createRequest($request)
    ), 
    $response
);
