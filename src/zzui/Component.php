<?php

namespace zzui;

use zzui\markup\ComponentTag;
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
   * @var \zzui\MarkupContainer|null
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

  public function handleComponentTag(
    Context $app,
    ComponentTag $tag
  ) {
    // should be implemented
  }

  public function render(Context $app)
  {
    // echo sprintf("%s::render()\n", get_class($this));

    $this->handleRender($app);
  }

  public function handleRender(Context $app)
  {
    // echo sprintf("%s::handleRender()\n", get_class($this));

    $markupStream = $this->findMarkupStream();

    $this->renderComponent($app, $markupStream);
  }

  public function renderComponent(
    Context $app,
    MarkupStream $markupStream
  ) {
    // echo sprintf("%s::renderComponent()\n", get_class($this));
  }

  public function findMarkupStream()
  {
    // echo sprintf("%s::findMarkupStream()\n", get_class($this));

    return $this->parent->findMarkupStream();
  }
}
