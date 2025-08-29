<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\ListenerRequestTarget;
use zzui\markup\html\FormListener;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupElement;

class Form extends MarkupContainer implements FormListener
{
  /**
   * Form submit handler.
   */
  private $onSubmitHandler;

  private $onValidationErrorsHandler;

  /**
   * Constructor.
   */
  public function __construct($id, $onSubmitHandler = null, $onValidationErrorsHandler = null)
  {
    parent::__construct($id);

    $this->onSubmitHandler = $onSubmitHandler === null
      ? function () {}
      : $onSubmitHandler;
    $this->onValidationErrorsHandler = $onValidationErrorsHandler === null
      ? function (array $errros) {}
      : $onValidationErrorsHandler;
  }

  /**
   * @see \zzui\markup\html\FormListener::onSubmit()
   */
  public function onSubmit(array $data = [])
  {
    $this->updateComponentModels($data);

    /** @var string[] */
    $errors = $this->validate();

    if (count($errors) !== 0) {
      call_user_func_array($this->onValidationErrorsHandler, [$errors]);
    } else {
      call_user_func_array($this->onSubmitHandler, []);
    }
  }

  public function updateComponentModels(array $data)
  {
    foreach ($this->children as $child) {
      $child->updateModel(isset($data[$child->id]) ? $data[$child->id] : null);
    }
  }

  /**
   * @return array
   */
  public function validate()
  {
    $errors = [];

    foreach ($this->children as $child) {
      foreach ($child->validate() as $message) {
        $errors[] = $message;
      }
    }

    return $errors;
  }

  public function handleRender(Context $ctx)
  {
    $this->markupStream = $this->findMarkupStream();

    $openTag = $this->markupStream->get();

    $this->handleComponentTag($ctx, $openTag);

    $ctx->getResponse()->write($openTag->__toString());
    $this->markupStream->next();

    while (
      $this->markupStream->hasMore()
      && !$this->markupStream->get()->closes($openTag)
    ) {

      $index = $this->markupStream->getCurrentIndex();

      $this->renderNext($ctx, $this->markupStream);

      if ($index === $this->markupStream->getCurrentIndex()) {
        throw new \Exception();
      }
    }

    $ctx->getResponse()->write($this->markupStream->get()->__toString());

    $this->markupStream->next();
  }

  /**
   * @see \zzui\markup\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $ctx, MarkupElement $tag)
  {
    $responsePage = $ctx->getResponsePage();

    $tag->attributes['method'] = 'POST';
    $tag->attributes['action'] = $ctx->getUrlCoder()->encode(
      new ListenerRequestTarget(
        is_array($responsePage) ? $responsePage[0] : get_class($responsePage),
        $tag->getId(),
        '\\zzui\markup\\html\\FormListener',
        []
      )
    );
  }
}
