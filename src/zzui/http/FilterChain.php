<?php

namespace zzui\http;

interface FilterChain
{
  public function doFilter(Request $request, Response $response);
}
