<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\Component;
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
   * @see \zzui\Component::renderComponent()
   */
  public function renderComponent(Context $app, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();

    $this->handleComponentTag($app, $openTag);

    $markupStream->next();

    $app->getResponse()->write($openTag->__toString());

    $inner = "";

    while (
      $markupStream->hasMore()
      && !$markupStream->get()->closes($openTag)
    ) {
      $this->parent->renderNext($app, $markupStream);
    }

    $app->getResponse()->write($inner);
    $app->getResponse()->write($markupStream->get()->__toString());

    $markupStream->next();
  }
}
