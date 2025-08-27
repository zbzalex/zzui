<?php

namespace zzui;

use zzui\http\HttpRequest;

/**
 * Url coder class.
 */
interface UrlCoder
{
  /**
   * Encode request target.
   */
  public function encode(RequestTarget $requestTarget);

  /**
   * Decode request and return request target.
   */
  public function decode(HttpRequest $request);
}
