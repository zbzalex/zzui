<?php

session_start();

use AwesomeProject\MyApplication;
use zzui\content\loader\FileLoader;
use zzui\content\ResourceManager;

require_once dirname(__DIR__) . "/vendor/autoload.php";

$app = new MyApplication(new ResourceManager([
  new FileLoader([
    dirname(__DIR__) . '/src/',
  ]),
]));

\zzui\zzui::run($app);
