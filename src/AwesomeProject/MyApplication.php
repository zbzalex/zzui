<?php

namespace AwesomeProject;

use Firebase\JWT\Key;
use AwesomeProject\ui\authorization\DefaultAuthorizationStrategy;
use AwesomeProject\ui\filters\PassportFilter;
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

  public function getJwtKey()
  {
    return new Key('secret', 'HS256');
  }

  public function setup(HttpRequest $request, HttpResponse $response, FilterChain $filterChain)
  {
    $this->setAuthorizationStrategy(new DefaultAuthorizationStrategy());

    $filterChain->addFilter(new PassportFilter($this));

    $this->mount(
      '/^\/forgotPassword/i',
      '\AwesomeProject\ui\pages\ForgotPasswordPage'
    );

  }
}
