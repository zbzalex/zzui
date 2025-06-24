<?php

namespace zzui\markup;

class Markup
{
  public $elements;

  public function __construct(array $elements = [])
  {
    $this->elements = $elements;
  }

  public function size()
  {
    return count($this->elements);
  }

  public function get($index)
  {
    if ($index < 0 || count($this->elements) - 1 < $index) {
      throw new \Exception("Out of bound array");
    }

    return $this->elements[$index];
  }

  public function addMarkupElement(MarkupElement $el)
  {
    $this->elements[] = $el;
  }

  public function __toString()
  {
    $output = "";
    foreach ($this->elements as $el) {
      $output .= $el instanceof ComponentTag ? $el->text : $el->__toString();
    }

    return $output;
  }
}
