<?php

namespace miru\presentation\filters;

use zzui\Context;
use zzui\http\Cookie;
use zzui\http\Filter;
use zzui\http\Request;
use zzui\http\Response;
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

  public function doFilter(Request $request, Response $response, FilterChain $chain)
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

    /** @var \zzui\http\Cookie $jwt */
    $jwt = count($results) != 0 ? $results[0] : null;
    if ($jwt !== null) {
      //$request->getSession()->setUser([]);
    }
    
    $chain->doFilter($request, $response);
  }
}
