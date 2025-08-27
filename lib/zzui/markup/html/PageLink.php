<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\markup\Component;
use zzui\ListenerRequestTarget;
use zzui\markup\ComponentTag;
use zzui\markup\MarkupStream;

/**
 * @author zbzalex
 */
class PageLink extends Component
{
  protected $pageClass;
  protected $params = [];

  public function __construct($id, $pageClass, array $params = [])
  {
    $this->id = $id;
    $this->pageClass = $pageClass;
    $this->params = $params;
  }

  public function getPageClass()
  {
    return $this->pageClass;
  }

  public function getParams()
  {
    return $this->params;
  }

  /**
   * @see \zzui\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $app, ComponentTag $tag)
  {
    $target = new ListenerRequestTarget($this->pageClass, null, null, $this->params);
    $tag->attributes['href'] = $app->getUrlCoder()->encode($target);
  }

  /**
   * @see \zzui\markup\Component::renderComponent()
   */
  public function renderComponent(Context $ctx, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();

    $this->handleComponentTag($ctx, $openTag);

    $markupStream->next();

    $ctx->getResponse()->write($openTag->__toString());

    $inner = "";

    while (
      $markupStream->hasMore()
      && !$markupStream->get()->closes($openTag)
    ) {
      $this->parent->renderNext($ctx, $markupStream);
    }

    $ctx->getResponse()->write($inner);
    $ctx->getResponse()->write($markupStream->get()->__toString());

    $markupStream->next();
  }
}
