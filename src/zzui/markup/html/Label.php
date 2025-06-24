<?php

namespace zzui\markup\html;

use zzui\Component;
use zzui\Context;
use zzui\markup\ComponentTag;
use zzui\markup\MarkupStream;

class Label extends Component
{
  private $value;

  public function __construct($id, $value)
  {
    parent::__construct($id);

    $this->value = $value;
  }

  public function setValue($value)
  {
    $this->value = $value;
  }

  public function getValue()
  {
    return $this->value;
  }

  /**
   * @see \zzui\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $app, ComponentTag $tag)
  {
    parent::handleComponentTag($app, $tag);
  }

  public function renderComponent(Context $app, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();

    $markupStream->next();
    while ($markupStream->hasMore() && !$markupStream->get()->closes($openTag)) $markupStream->next();

    $app->getResponse()->write($openTag->__toString());
    $app->getResponse()->write($this->value);
    $app->getResponse()->write($markupStream->get()->__toString());

    $markupStream->next();
  }
}
