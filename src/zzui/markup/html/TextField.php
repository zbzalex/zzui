<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\markup\ComponentTag;

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
   * @see \zzui\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $app, ComponentTag $tag)
  {
    $tag->attributes['name']  = $this->id;
    $tag->attributes['value'] = $this->defaultValue;
  }

  public function handleRender(Context $app)
  {
    $markupStream = $this->findMarkupStream();

    $tag = $markupStream->get();

    $markupStream->next();

    $app->getResponse()->write($tag->__toString());
  }
}
