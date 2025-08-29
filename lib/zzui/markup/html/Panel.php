<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\markup\Markup;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupParser;
use zzui\markup\MarkupStream;
use zzui\markup\RawMarkup;

/**
 * Panel component.
 * 
 * @author zbzalex
 */
abstract class Panel extends MarkupContainer
{
  public function handleRender(Context $ctx)
  {
    // parent markup stream
    $parent = $this->findMarkupStream();

    $openTag = $parent->get();

    // unset($openTag->attributes['view-id']);

    $parent->next();
    $parent->skipToMatchCloseTag($openTag);





    $markupResource = $this->getMarkupResource();

    $html = $ctx->getResourceManager()->load($markupResource);
    $markup = MarkupParser::parse($html);
    $this->markupStream = new MarkupStream($markup);

    $ctx->getResponse()->write($openTag->__toString());

    $this->renderAll($ctx, $this->markupStream);
    
    $ctx->getResponse()->write($parent->get()->__toString());
    $parent->next();
  }
}
