<?php

use RestfulWS\App;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__ . '/vendor/autoload.php';

$app = App::create();
$request = Request::createFromGlobals();
$response = $app->handle($request);
$response->send();
$app->down();
