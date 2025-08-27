<?php

namespace zzui\http;

class Cookie
{
  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $value;

  /**
   * @param string $name
   * @param string $value
   */
  public function __construct($name, $value)
  {
    $this->name = $name;
    $this->value = $value;
  }
}
