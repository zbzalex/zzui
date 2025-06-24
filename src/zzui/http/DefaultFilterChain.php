<?php

namespace zzui\http;

class DefaultFilterChain implements FilterChain
{
  protected $filters;
  protected $index;

  public function __construct()
  {
    $this->filters = [];
    $this->index = 0;
  }

  public function addFilter(Filter $filter)
  {
    $this->filters[] = $filter;
  }

  public function doFilter(Request $request, Response $response)
  {
    if ($this->index < count($this->filters)) {
      $filter = $this->filters[$this->index++];
      $filter->doFilter($request, $response, $this);
    }
  }
}
