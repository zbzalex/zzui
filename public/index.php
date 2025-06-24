<?php

session_start();

use miru\presentation\authorization\DefaultAuthorizationStrategy;
use miru\presentation\filters\PassportFilter;
use miru\WebApplication;
use zzui\content\loader\FileLoader;
use zzui\content\ResourceManager;
use zzui\RequestProcessorFilter;
use zzui\http\DefaultFilterChain;
use zzui\http\Request;
use zzui\http\Response;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$app = new WebApplication(new ResourceManager([
  new FileLoader([
    dirname(__DIR__) . '/src/',
  ]),
]));

$app->setAuthorizationStrategy(new DefaultAuthorizationStrategy());

$request = Request::createFromGlobals();

$response = new Response();

$filterChain = new DefaultFilterChain();
$filterChain->addFilter(new PassportFilter($app));
$filterChain->addFilter(new RequestProcessorFilter($app));

$filterChain->doFilter($request, $response);

$response->send();
