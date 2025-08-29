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

  public static function merge(
    Markup $child,
    Markup $parent
  ) {
    $markup = new Markup();
    $stream = new MarkupStream($parent);

    $childrenIndex = -1;

    while ($stream->hasMore()) {
      $tag = $stream->get();
      if ($tag instanceof RawMarkup) {
        $markup->addMarkupElement($tag);
        $stream->next();
      } else {
        switch ($tag->type) {
          case 'open':
          case 'close':
            $markup->addMarkupElement($tag);
            $stream->next();
            break;
          case 'open_close':
            if ($tag->name === 'children') {

              $childrenIndex = $stream->getCurrentIndex();

              $stream->next();

              $childStream = new MarkupStream($child);
              while ($childStream->hasMore()) {
                $childTag = $childStream->get();
                $markup->addMarkupElement($childTag);
                $childStream->next();
              }
            } else {
              $markup->addMarkupElement($tag);
              $stream->next();
            }
            break;
        }
      }
    }

    if ($childrenIndex === -1) {
      throw new \Exception(
        "children tag expected"
      );
    }

    return $markup;
  }
}
