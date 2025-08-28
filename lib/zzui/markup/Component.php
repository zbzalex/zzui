<?php

namespace zzui\markup;

use zzui\Context;
use zzui\markup\MarkupStream;

/**
 * Abstract component class.
 * 
 * @author zbzalex
 */
abstract class Component
{
  /**
   * @var string
   */
  protected $id;

  /**
   * @var boolean
   */
  protected $visible;

  /**
   * @var \zzui\markup\MarkupContainer|null
   */
  protected $parent;

  public function __construct($id = null)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function setId($id)
  {
    $this->id = $id;
  }

  public function isVisible()
  {
    return $this->visible;
  }

  public function setVisible($visible)
  {
    $this->visible = $visible;
  }

  public function setParent(Component $parent)
  {
    $this->parent = $parent;
  }

  /**
   * @return \zzui\markup\MarkupContainer|null
   */
  public function getParent()
  {
    return $this->parent;
  }

  public function handleComponentTag(Context $ctx, MarkupElement $tag) {
    // should be implemented
  }

  public function render(Context $ctx)
  {
    // echo sprintf("%s::render()\n", get_class($this));

    $this->handleRender($ctx);
  }

  public function handleRender(Context $ctx)
  {
    // echo sprintf("%s::handleRender()\n", get_class($this));

    $markupStream = $this->findMarkupStream();

    $this->renderComponent($ctx, $markupStream);
  }

  public function renderComponent(Context $ctx, MarkupStream $markupStream)
  {
    // echo sprintf("%s::renderComponent()\n", get_class($this));
  }

  /**
   * @return \zzui\markup\MarkupStream|null
   */
  public function findMarkupStream()
  {
    return $this->parent !== null ? $this->parent->findMarkupStream() : null;
  }
}
