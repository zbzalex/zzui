<?php

namespace AwesomeProject;

use AwesomeProject\ui\pages\HomePage;
use zzui\CryptedUrlCoder;
use zzui\http\FilterChain;
use zzui\http\HttpRequest;
use zzui\http\HttpResponse;

class MyApplication extends \zzui\Application
{
  public function getSecretKey()
  {
    return 'my-secret-key';
  }

  public function getHomePage()
  {
    return HomePage::class;
  }

  public function getUrlCoder()
  {
    return new CryptedUrlCoder($this->getSecretKey());
  }

  public function setup(HttpRequest $request, HttpResponse $response, FilterChain $filterChain) {}
}
