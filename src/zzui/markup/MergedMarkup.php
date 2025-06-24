<?php

namespace zzui\markup;

class MergedMarkup extends Markup
{
  public function merge(Markup $markup, Markup $baseMarkup)
  {
    $markupStream = new MarkupStream($markup);
    $baseMarkupStream = new MarkupStream($baseMarkup);

    $childIndex = -1;
    $el = $baseMarkupStream->get();
    while ($baseMarkupStream->hasMore()) {
      if ($el instanceof ComponentTag) {
        $componentTag = $el;

        if ($componentTag->id === 'child') {
          $childIndex = $baseMarkupStream->getCurrentIndex();

          $baseMarkupStream->next();

          while (
            $baseMarkupStream->hasMore()
            && !$baseMarkupStream->get()->closes($el)
          ) {
            $baseMarkupStream->next();
          }

          $baseMarkupStream->next();

          break;
        }
      }

      $this->addMarkupElement($el);

      $el = $baseMarkupStream->next();
    }

    if ($childIndex === -1) {
      throw new \Exception('tag id: "child" was not found');
    }

    $el = $markupStream->get();
    while ($markupStream->hasMore()) {
      $this->addMarkupElement($el);
      $el = $markupStream->next();
    }

    $el = $baseMarkupStream->get();
    while ($baseMarkupStream->hasMore()) {
      $this->addMarkupElement($el);
      $el = $baseMarkupStream->next();
    }
  }
}
