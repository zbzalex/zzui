<?php

namespace zzui\markup;

use zzui\markup\Component;
use zzui\Context;

/**
 * Markup container.
 * 
 * @author zbzalex
 */
class MarkupContainer extends Component
{
  /**
   * @var Component[]
   */
  protected $children = [];

  /**
   * @var \zzui\markup\MarkupStream
   */
  protected $markupStream;

  /**
   * @param Component $child
   */
  public function add($child)
  {
    $this->children[] = $child;
    $child->setParent($this);
  }

  public function getChildren()
  {
    return $this->children;
  }

  public function removeChild($id)
  {
    $this->children = array_filter($this->children, function ($child) use ($id) {
      return $child->getId() !== $id;
    });
  }

  public function findChildById($id)
  {
    $results = array_values(array_filter($this->children, function ($child) use ($id) {
      return $child->getId() === $id;
    }));

    return count($results) > 0 ? $results[0] : null;
  }

  public function getMarkupResource()
  {
    return str_replace("\\", "/", get_class($this)) . ".html";
  }

  public function getMarkupStream()
  {
    return $this->markupStream;
  }

  public function setMarkupStream(MarkupStream $markupStream)
  {
    $this->markupStream = $markupStream;
  }

  public function findMarkupStream()
  {
    $current = $this;
    while ($current->getMarkupStream() === null) {
      $current = $current->getParent();
      if ($current === null) {
        throw new \Exception();
      }
    }

    return $current->getMarkupStream();
  }

  /**
   * @see \zzui\Component::handleRender()
   */
  public function handleRender(Context $app)
  {
    $this->renderAll($app, $this->markupStream);
  }

  public function renderAll(Context $ctx, MarkupStream $markupStream)
  {
    while ($markupStream->hasMore()) {

      $index = $markupStream->getCurrentIndex();

      $this->renderNext($ctx, $markupStream);

      if ($index === $markupStream->getCurrentIndex()) {
        throw new \Exception(
          "markup stream index failed to advance"
        );
      }
    }
  }

  public function renderNext(Context $ctx, MarkupStream $markupStream)
  {
    $el = $markupStream->get();

    if ($el->getId() !== null) {

      /** @var \zzui\markup\ComponentTag $componentTag */
      $componentTag = $el;

      /** @var \zzui\markup\Component $component */
      $component = $this->findChildById($componentTag->id);
      if ($component === null) {
        throw new \Exception("Could not found component");
      }

      $component->handleComponentTag($ctx, $componentTag);
      $component->render($ctx);
      
    } else if ($el instanceof RawMarkup) {

      $ctx->getResponse()->write($el->__toString());

      $markupStream->next();
      
    }
  }
}
