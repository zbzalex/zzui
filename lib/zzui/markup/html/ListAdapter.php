<?php

namespace zzui\markup\html;

use zzui\markup\Component;
use zzui\Context;
use zzui\markup\Markup;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupStream;

class ListAdapter extends Component
{
  protected $items;
  protected $onPopulateItemHandler;

  public function __construct($id, array $items, $onPopulateItemHandler)
  {
    parent::__construct($id);

    $this->items = $items;
    $this->onPopulateItemHandler = $onPopulateItemHandler;
  }

  public function setItems(array $items)
  {
    $this->items = $items;
  }

  public function handleRender(Context $app)
  {
    $markupStream = $this->findMarkupStream();

    $openTag = $markupStream->get();
    $app->getResponse()->write($markupStream->get()->__toString());
    $markupStream->next();

    $itemMarkup = new Markup();
    while ($markupStream->hasMore() && !$markupStream->get()->closes($openTag)) {
      $itemMarkup->addMarkupElement($markupStream->get());
      $markupStream->next();
    }

    foreach ($this->items as $item) {
      $itemMarkupStream = new MarkupStream($itemMarkup);
      $itemMarkupContainer = new MarkupContainer();
      $itemMarkupContainer->setMarkupStream($itemMarkupStream);
      $this->populateItem($item, $itemMarkupContainer);
      $itemMarkupContainer->render($app);
    }

    $app->getResponse()->write($markupStream->get()->__toString());
    $markupStream->next();
  }

  public function populateItem($item, MarkupContainer $itemMarkupContainer)
  {
    call_user_func_array($this->onPopulateItemHandler, [
      $item,
      $itemMarkupContainer,
    ]);
  }
}
