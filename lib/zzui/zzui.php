<?php

namespace zzui;

use zzui\http\DefaultFilterChain;
use zzui\http\HttpRequest;
use zzui\http\HttpResponse;

class zzui
{
  public static function getVersion()
  {
    return '0.1.0';
  }

  public static function run(
    Application $app
  )
  {
    $request = HttpRequest::createFromGlobals();
    $response = new HttpResponse();

    $filterChain = new DefaultFilterChain();
    $filterChain->addFilter(new RequestProcessorFilter($app));
    $filterChain->doFilter($request, $response);

    $response->send();
  }
}
