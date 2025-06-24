<?php

namespace zzui\markup\html;

use zzui\Component;
use zzui\Context;
use zzui\ListenerRequestTarget;
use zzui\markup\ComponentTag;
use zzui\markup\MarkupStream;

/**
 * Link component.
 * 
 * @author zbzalex
 */
class Link extends Component implements LinkListener
{
  protected $onClickHandler;

  public function __construct($id, $onClickHandler)
  {
    parent::__construct($id);

    $this->onClickHandler = $onClickHandler;
  }

  /**
   * @see \zzui\markup\html\LinkListener::onClick()
   */
  public function onClick()
  {
    call_user_func_array($this->onClickHandler, []);
  }

  /**
   * @see \zzui\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $app, ComponentTag $tag)
  {
    // echo sprintf("%s::handleComponentTag()\n", get_class($this));

    $responsePage = $app->getResponsePage();

    $target = new ListenerRequestTarget(
      is_array($responsePage) ? $responsePage[0] : get_class($responsePage),
      $tag->id,
      '\\zzui\\markup\\html\\LinkListener',
      []
    );
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

    // render tag
    $app->getResponse()->write($openTag->__toString());

    $inner = null;

    while (
      $markupStream->hasMore()
      && !$markupStream->get()->closes($openTag)
    ) {
      $this->parent->renderNext($app, $markupStream);
    }

    $app->getResponse()->write($inner);

    // render close tag
    $app->getResponse()->write($markupStream->get()->__toString());
    $markupStream->next();
  }
}
