<?php

namespace zzui\markup;

/**
 * @author Sasha Broslavskiy
 */
class ComponentTag extends MarkupElement
{
  /**
   * @var string
   */
  public $id;

  public function __toString()
  {
    $output = [];
    if ($this->isOpen() || $this->isOpenClose()) {
      if (count($this->attributes) > 0) {
        foreach ($this->attributes as $attr => $value) {
          $output[] = $value === null ? $attr : sprintf("%s=\"%s\"", $attr, $value);
        }
      }
      return "<" . $this->name . (count($output) > 0 ? " " . implode(" ", $output) : null) . ($this->isOpenClose() ? "/>" : ">");
    } else if ($this->isClose()) {
      return sprintf("</%s>", $this->name);
    }
  }
}
