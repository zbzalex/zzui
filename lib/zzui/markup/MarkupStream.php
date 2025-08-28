<?php

namespace zzui\markup;

class MarkupStream
{
  /**
   * @var \zzui\markup\Markup
   */
  protected $markup;

  /**
   * @var int
   */
  protected $currentIndex = 0;

  /**
   * @var \zzui\markup\MarkupElement
   */
  protected $current;

  /**
   * Constructor.
   * 
   * @param \zzui\markup\Markup $markup
   */
  public function __construct(Markup $markup)
  {
    $this->markup = $markup;

    if ($markup->size() > 0) {
      $this->current = $markup->get(0);
    }
  }
  
  public function size()
  {
    return $this->markup->size();
  }

  public function hasMore()
  {
    return $this->currentIndex < $this->markup->size();
  }

  /**
   * @return \zzui\markup\MarkupElement|null
   */
  public function next()
  {
    if (++$this->currentIndex < $this->markup->size()) {
      $this->current = $this->markup->get($this->currentIndex);
      return $this->current;
    }

    return null;
  }

  /**
   * @return \zzui\markup\MarkupElement|null
   */
  public function get()
  {
    return $this->current;
  }

  public function getCurrentIndex()
  {
    return $this->currentIndex;
  }

  public function skipRawMarkup()
  {
    while ($this->current instanceof RawMarkup) {
      $this->next();
    }
  }

  public function skipComponent()
  {
    $tag = $this->get();

    if ($tag->isOpen()) {
      $this->next();
      $this->skipToMatchCloseTag($tag);
      $this->next();
    } else if ($tag->isOpenClose()) {
      $this->next();
    } else {
      throw new \Exception("bad markup element");
    }
  }

  public function skipToMatchCloseTag(MarkupElement $openTag)
  {
    while ($this->hasMore()) {

      $el = $this->get();
      
      if ($el->closes($openTag)) {
        break;
      } else {
        $this->next();
      }
    }
  }

  public function reset()
  {
    $this->current = $this->markup->get(0);
    $this->currentIndex = 0;
  }
}
