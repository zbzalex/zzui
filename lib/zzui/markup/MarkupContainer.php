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
  public function add(Component $child)
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
    $this->children = array_filter(
      $this->children,
      function ($child) use ($id) {
        return $child->getId() !== $id;
      }
    );
  }

  public function findChildById($id)
  {
    $results = array_values(
      array_filter(
        $this->children,
        function ($child) use ($id) {
          return $child->getId() == $id;
        }
      )
    );

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
   * @see \zzui\markup\Component::handleRender()
   */
  public function handleRender(Context $ctx)
  {
    $this->renderAll($ctx, $this->markupStream);
  }

  public function renderAll(Context $ctx, MarkupStream $stream)
  {
    while ($stream->hasMore()) {

      $index = $stream->getCurrentIndex();

      $this->renderNext($ctx, $stream);

      if ($index === $stream->getCurrentIndex()) {
        throw new \Exception(
          "Infinity loop"
        );
      }
    }
  }

  public function renderNext(Context $ctx, MarkupStream $stream)
  {
    $el = $stream->get();

    if ($el instanceof RawMarkup) {

      $ctx->getResponse()->write($el->__toString());
      $stream->next();
    } else if ($el->getId() !== null) {

      /** @var \zzui\markup\Component $component */
      $component = $this->findChildById($el->getId());

      if ($component === null) {
        throw new \Exception(
          "Expected component"
        );
      }

      $component->handleComponentTag($ctx, $el);
      $component->render($ctx);
    }
  }
}
