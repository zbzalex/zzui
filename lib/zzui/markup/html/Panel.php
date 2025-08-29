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
    
    unset($openTag->attributes['view-id']);

    $this->markupStream->next();
    $this->markupStream->skipToMatchCloseTag($openTag);




    $markupResource = $this->getMarkupResource();

    $html = $ctx->getResourceManager()->load($markupResource);
    $markup = MarkupParser::parse($html);
    $markupStream = new MarkupStream($markup);

    $ctx->getResponse()->write($openTag->__toString());

    // $this->renderAll($ctx, $markupStream);

    $ctx->getResponse()->write($this->markupStream->get()->__toString());
    $this->markupStream->next();

  }
}
