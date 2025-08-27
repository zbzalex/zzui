<?php

namespace zzui\markup;

/**
 * @author Sasha Broslavskiy
 */
class RawMarkup extends MarkupElement
{
  public function __construct($text)
  {
    $this->text = $text;
  }

  public function __toString()
  {
    return $this->text;
  }
}
