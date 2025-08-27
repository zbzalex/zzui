<?php

namespace zzui\http;

interface Filter
{
  public function doFilter(HttpRequest $request, HttpResponse $response, FilterChain $chain);
}
