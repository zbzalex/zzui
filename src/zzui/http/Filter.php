<?php

namespace zzui\http;

interface Filter
{
  public function doFilter(Request $request, Response $response, FilterChain $chain);
}
