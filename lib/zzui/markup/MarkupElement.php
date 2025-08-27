<?php

namespace zzui\markup;

/**
 * Base markup element.
 * 
 * @author zbzalex
 */
abstract class MarkupElement
{
  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $type;
  
  public $attributes;
  public $closes;
  public $pos = 0;
  public $text;
  public $len = 0;

  public function closes(MarkupElement $open)
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

  public function isChildren()
  {
    return $this->name === 'children';
  }

  public function __toString()
  {
    return "";
  }
}
