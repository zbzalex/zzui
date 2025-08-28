<?php

namespace zzui\markup;

class MarkupMerger
{
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
