<?php

namespace zzui\markup;

/**
 * Base markup element.
 * 
 * @author zbzalex
 */
class MarkupElement
{
  /**
   * @var string
   */
  public $name;

  /**
   * @var string
   */
  public $type;

  /**
   * @var array
   */
  public $attributes;

  /**
   * @var \zzui\markup\MarkupElement|null
   */
  public $closes;

  /**
   * @var int
   */
  public $pos = 0;

  /**
   * @var string|null
   */
  public $text = null;

  /**
   * @var int
   */
  public $len = 0;
  
  public function __construct() {}

  public function closes(MarkupElement $openTag)
  {
    return $openTag->name == $this->name;
  }

  public function closeTag()
  {
    $tag = new MarkupElement();
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
    $output = [];
    if ($this->isOpen() || $this->isOpenClose()) {
      // if (count($this->attributes) > 0) {
      foreach ($this->attributes as $attr => $value) {
        $output[] = $value === null
          ? $attr
          : sprintf("%s=\"%s\"", $attr, $value);
      }
      // }
      return "<" . $this->name . (count($output) > 0 ? " " . implode(" ", $output) : null) . ($this->isOpenClose() ? "/>" : ">");
    } else if ($this->isClose()) {
      return sprintf("</%s>", $this->name);
    }
  }

  public function getId()
  {
    return isset($this->attributes['view-id']) ? $this->attributes['view-id'] : null;
  }
}
