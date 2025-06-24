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
  public function handleRender(Context $app)
  {
    // echo sprintf("%s::handleRender()\n", get_class($this));

    $this->markupStream = $this->findMarkupStream();

    $openTag = $this->markupStream->get();

    // $app->getResponse()->write($openTag->text);

    $this->markupStream->next();
    $this->markupStream->skipRawMarkup();

    $markupResource = $this->getMarkupResource();
    $markup = new Markup(MarkupParser::parse($app->getResourceManager()->load($markupResource)));
    $markupStream = new MarkupStream($markup);

    $this->renderAll($app, $markupStream);

    // $app->getResponse()->write($this->markupStream->get()->text);
    $this->markupStream->next();
  }
}
