<?php

namespace zzui\markup;

class Markup
{
  /**
   * @var MarkupElement[]
   */
  public $elements;

  /**
   * @param MarkupElement[] $elements
   */
  public function __construct(array $elements = [])
  {
    $this->elements = $elements;
  }

  /**
   * @return int
   */
  public function size()
  {
    return count($this->elements);
  }

  /**
   * 
   * @param int $index
   * 
   * @return MarkupElement
   * 
   * @throws \RuntimeException
   */
  public function get($index)
  {
    if ($index < 0 || count($this->elements) - 1 < $index) {
      throw new \RuntimeException(
        "Bad index"
      );
    }

    return $this->elements[$index];
  }

  public function addMarkupElement(MarkupElement $el)
  {
    $this->elements[] = $el;
  }

  public function __toString()
  {
    $output = [];
    foreach ($this->elements as $el) {
      $output[] = $el->__toString();
    }

    return implode("", $output);
  }
}
