<?php

namespace zzui\markup\html;

use zzui\markup\Component;
use zzui\Context;
use zzui\markup\ComponentTag;
use zzui\markup\MarkupStream;

class Label extends Component
{
  /**
   * @var string
   */
  private $value;

  /**
   * @param string $id
   * @param string $value
   */
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
   * @see \zzui\markup\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $ctx, ComponentTag $tag)
  {
    parent::handleComponentTag($ctx, $tag);
  }

  public function renderComponent(Context $ctx, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();

    $markupStream->next();

    while ($markupStream->hasMore() && !$markupStream->get()->closes($openTag)) $markupStream->next();

    $ctx->getResponse()->write($openTag->__toString());
    $ctx->getResponse()->write($this->value);
    $ctx->getResponse()->write($markupStream->get()->__toString());

    $markupStream->next();
  }
}
