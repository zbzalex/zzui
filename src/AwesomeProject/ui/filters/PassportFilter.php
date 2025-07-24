<?php

namespace AwesomeProject\ui\filters;

use zzui\Context;
use zzui\http\Cookie;
use zzui\http\Filter;
use zzui\http\HttpRequest;
use zzui\http\HttpResponse;
use zzui\http\FilterChain;

class PassportFilter implements Filter
{
  /**
   * @var \zzui\Context
   */
  private $ctx;

  public function __construct(Context $ctx)
  {
    $this->ctx = $ctx;
  }

  public function doFilter(HttpRequest $request, HttpResponse $response, FilterChain $chain)
  {
    /** @var \zzui\http\Cookie[] $cookies */
    $cookies = $request->getCookies();

    /** @var \zzui\http\Cookie[] $results */
    $results = array_filter(
      $cookies,
      function (Cookie $cookie) {
        if ($cookie->name === 'JWT') {
          return $cookie;
        }
      }
    );
    
    $results = array_values($results);

    /** @var \zzui\http\Cookie $jwt */
    $jwt = count($results) != 0 ? $results[0] : null;
    if ($jwt !== null) {
      //$request->getSession()->setUser([]);
    }
    
    $chain->doFilter($request, $response);
  }
}
