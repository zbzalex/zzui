<?php

namespace zzui\http;

class Cookie
{
  public $name;
  public $value;

  public function __construct($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }
}
