<?php

namespace zzui\markup;

use zzui\Component;
use zzui\Context;

/**
 * Markup container.
 * 
 * @author zbzalex
 */
class MarkupContainer extends Component
{
  protected $children = [];

  /**
   * @var \zzui\markup\MarkupStream
   */
  protected $markupStream;

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

  public function renderAll(Context $app, MarkupStream $markupStream)
  {
    // echo sprintf("%s::renderAll()\n", get_class($this));

    while ($markupStream->hasMore()) {
      $index = $markupStream->getCurrentIndex();

      // echo sprintf("current index: %d\n", $index);

      $this->renderNext($app, $markupStream);

      if ($index === $markupStream->getCurrentIndex()) {
        throw new \Exception("markup stream index failed to advance");
      }
    }
  }

  public function renderNext(Context $app, MarkupStream $markupStream)
  {
    // echo sprintf("%s::renderNext()\n", get_class($this));

    $el = $markupStream->get();
    if ($el instanceof ComponentTag) {

      /** @var \zzui\markup\ComponentTag $componentTag */
      $componentTag = $el;

      /** @var \zzui\Component $component */
      $component = $this->findChildById($componentTag->id);

      if ($component === null) {
        throw new \Exception(sprintf(
          "component for %s not found",
          $componentTag->id
        ));
      }

      $component->handleComponentTag($app, $componentTag);

      // echo sprintf("%s id=%s\n", $componentTag->name, $componentTag->id);

      $component->render($app);
    } else {

      // echo "raw markup\n";

      $app->getResponse()->write($el->__toString());
      $markupStream->next();
    }
  }
}
