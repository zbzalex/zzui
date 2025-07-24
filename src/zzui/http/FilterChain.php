<?php

namespace zzui\http;

interface FilterChain
{
  public function addFilter(Filter $filter);
  
  public function doFilter(HttpRequest $request, HttpResponse $response);
}
