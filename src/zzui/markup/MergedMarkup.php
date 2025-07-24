<?php

namespace zzui\markup;

class MergedMarkup extends Markup
{
  public function merge(Markup $markup, Markup $baseMarkup)
  {
    $markupStream = new MarkupStream($markup);
    $baseMarkupStream = new MarkupStream($baseMarkup);

    $childrenIndex = -1;
    $el = $baseMarkupStream->get();
    while ($baseMarkupStream->hasMore()) {

      if ($el instanceof ComponentTag) {

        $componentTag = $el;

        if ($componentTag->isChildren()) {

          $childrenIndex = $baseMarkupStream->getCurrentIndex();

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

    if ($childrenIndex === -1) {
      throw new \Exception('tag "children" was not found');
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
