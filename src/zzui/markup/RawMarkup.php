<?php

namespace zzui\markup;

class RawMarkup extends MarkupElement
{
  public $str;

  public function __construct($str)
  {
    $this->str = $str;
  }

  public function __toString()
  {
    return $this->str;
  }
}
