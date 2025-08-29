<?php

namespace zzui\markup\html;

use zzui\markup\Component;
use zzui\Context;
use zzui\markup\MarkupStream;

/**
 * @author zbzalex
 */
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

  public function renderComponent(Context $ctx, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();

    // unset($openTag->attributes['view-id']);

    $markupStream->next();
    $markupStream->skipToMatchCloseTag($openTag);

    $ctx->getResponse()->write($openTag->__toString());
    $ctx->getResponse()->write($this->value);
    $ctx->getResponse()->write($markupStream->get()->__toString());

    $markupStream->next();
  }
}
