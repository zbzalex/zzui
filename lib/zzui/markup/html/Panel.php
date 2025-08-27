<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\markup\Markup;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupParser;
use zzui\markup\MarkupStream;

/**
 * Panel component.
 * 
 * @author zbzalex
 */
abstract class Panel extends MarkupContainer
{
  public function handleRender(Context $ctx)
  {
    $this->markupStream = $this->findMarkupStream();

    $openTag = $this->markupStream->get();
    $ctx->getResponse()->write($openTag->__toString());

    $this->markupStream->next();
    // $this->markupStream->skipRawMarkup();

    while (
      $this->markupStream->hasMore()
      && !$this->markupStream->get()->closes($openTag)
    ) {
      $this->markupStream->next();
    }


    $markupResource = $this->getMarkupResource();

    $html = $ctx->getResourceManager()->load($markupResource);
    $elements = MarkupParser::parse($html);
    $markup = new Markup($elements);
    $markupStream = new MarkupStream($markup);


    $this->renderAll($ctx, $markupStream);




    $ctx->getResponse()->write($this->markupStream->get()->__toString());

    $this->markupStream->next();
  }
}
