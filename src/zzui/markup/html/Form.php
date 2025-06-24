<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\ListenerRequestTarget;
use zzui\markup\ComponentTag;
use zzui\markup\html\FormListener;
use zzui\markup\MarkupContainer;

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

  public function handleRender(Context $app)
  {
    // echo sprintf("%s::handleRender()\n", get_class($this));

    $this->markupStream = $this->findMarkupStream();

    $openTag = $this->markupStream->get();

    $this->handleComponentTag($app, $openTag);

    $app->getResponse()->write($openTag->__toString());
    $this->markupStream->next();

    while (
      $this->markupStream->hasMore()
      && !$this->markupStream->get()->closes($openTag)
    ) {

      $index = $this->markupStream->getCurrentIndex();

      $this->renderNext($app, $this->markupStream);

      if ($index === $this->markupStream->getCurrentIndex()) {
        throw new \Exception();
      }
    }

    $app->getResponse()->write($this->markupStream->get()->__toString());

    $this->markupStream->next();
  }

  /**
   * @see \zzui\Component::handleComponentTag()
   */
  public function handleComponentTag(Context $app, ComponentTag $tag)
  {
    $responsePage = $app->getResponsePage();

    $tag->attributes['method'] = 'POST';
    $tag->attributes['action'] = $app->getUrlCoder()->encode(
      new ListenerRequestTarget(
        is_array($responsePage) ? $responsePage[0] : get_class($responsePage),
        $tag->id,
        '\\zzui\markup\\html\\FormListener',
        []
      )
    );
  }
}
