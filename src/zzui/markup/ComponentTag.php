<?php

namespace zzui\markup;

class ComponentTag extends MarkupElement
{
  public $name;
  public $type;

  public $pos;
  public $text;
  public $len;

  public $attributes;
  public $id;
  public $closes;

  /**
   * @see \zzui\markup\MarkupElement::closes()
   */
  public function closes(ComponentTag $open)
  {
    return $open->name === $this->name;
  }

  public function closeTag()
  {
    $tag = new ComponentTag();
    $tag->type = 'close';
    $tag->name = $this->name;

    return $tag;
  }

  public function requiresCloseTag()
  {
    return !in_array($this->name, [
      'p',
      'img',
      'input',
      'br',
    ]);
  }

  public function isOpen()
  {
    return $this->type === 'open';
  }

  public function isClose()
  {
    return $this->type === 'close';
  }

  public function isOpenClose()
  {
    return $this->type === 'open_close';
  }
  
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
