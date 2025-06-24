<?php

namespace zzui\markup;

class MarkupStream
{
  protected $markup;
  protected $currentIndex = 0;
  protected $current;

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

  public function next()
  {
    if (++$this->currentIndex < $this->markup->size()) {
      $this->current = $this->markup->get($this->currentIndex);
      return $this->current;
    }

    return null;
  }

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

  public function skipToMatchCloseTag(ComponentTag $openTag)
  {
    while ($this->hasMore()) {
      if ($this->get()->closes($openTag)) {
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
