<?php

namespace zzui\markup\html;

use zzui\markup\Component;
use zzui\Context;
use zzui\ListenerRequestTarget;
use zzui\markup\MarkupElement;
use zzui\markup\MarkupStream;

/**
 * Link component.
 * 
 * @author zbzalex
 */
class Link extends Component implements LinkListener
{
  protected $onClickHandler;

  /**
   * Constructor.
   * 
   * @param string $id
   * @param mixed $onClickHandler
   */
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
   * @see \zzui\markup\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $ctx, MarkupElement $tag)
  {
    // echo sprintf("%s::handleComponentTag()\n", get_class($this));

    $responsePage = $ctx->getResponsePage();

    $target = new ListenerRequestTarget(
      is_array($responsePage) ? $responsePage[0] : get_class($responsePage),
      $tag->getId(),
      '\\zzui\\markup\\html\\LinkListener',
      []
    );
    $tag->attributes['href'] = $ctx->getUrlCoder()->encode($target);
  }

  /**
   * @see \zzui\markup\Component::renderComponent()
   */
  public function renderComponent(Context $ctx, MarkupStream $markupStream)
  {
    $openTag = $markupStream->get();
    // unset($openTag->attributes['view-id']);

    $this->handleComponentTag($ctx, $openTag);

    $markupStream->next();

    $ctx->getResponse()->write($openTag->__toString());

    $inner = null;

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
