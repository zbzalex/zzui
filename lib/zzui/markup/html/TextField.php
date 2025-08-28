<?php

namespace zzui\markup\html;

use zzui\Context;

class TextField extends FormComponentImpl
{
  protected $defaultValue;

  public function __construct($id, $defaultValue = null, array $validators = [])
  {
    parent::__construct($id);

    $this->defaultValue = $defaultValue;
    $this->validators = $validators;
  }

  /**
   * @see \zzui\markup\Component::handleComponentTag()
   * 
   * @param \zzui\Context $ctx
   * @param \zzui\markup\MarkupElement $tag
   */
  public function handleComponentTag(Context $ctx, MarkupElement $tag)
  {
    $tag->attributes['name']  = $this->id;
    $tag->attributes['value'] = $this->defaultValue;
  }

  public function handleRender(Context $ctx)
  {
    $markupStream = $this->findMarkupStream();

    $tag = $markupStream->get();

    $markupStream->next();

    $ctx->getResponse()->write($tag->__toString());
  }
}
