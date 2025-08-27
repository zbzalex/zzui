<?php

namespace zzui\markup\html;

use zzui\Context;
use zzui\markup\Markup;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupParser;
use zzui\markup\MarkupStream;
use zzui\markup\MergedMarkup;

/**
 * Base page class.
 * 
 * @author zbzalex
 */
abstract class Page extends MarkupContainer
{
  protected $app;

  /**
   * Page params.
   * @var array
   */
  protected $params;

  /**
   * Page markup
   * 
   * @var \zzui\markup\MarkupStream
   */
  protected $markupStream;
  
  /**
   * Constructor.
   */
  public function __construct(Context $app, array $params = [])
  {
    $this->app = $app;
    $this->params = $params;
  }

  public function getParams()
  {
    return $this->params;
  }

  public function handleRender(Context $app)
  {
    // echo sprintf("%s::handleRender()\n", get_class($this));

    $hierarchy      = [];
    $parentClass    = get_class($this);
    while ($parentClass != Page::class) {
      array_unshift($hierarchy, $parentClass);
      // $hierarchy[] = $parentClass;
      $parentClass = get_parent_class($parentClass);
    }

    $markup = null;
    foreach ($hierarchy as $pageClass) {
      $resource = str_replace("\\", "/", $pageClass) . ".html";
      $content = $this->app->getResourceManager()->load($resource);

      if ($markup === null) {
        $markup = new Markup(MarkupParser::parse($content));
      } else {
        $baseMarkup = $markup;
        $markup = new MergedMarkup();
        $markup->merge(
          new Markup(MarkupParser::parse($content)),
          $baseMarkup
        );
      }
    }

    $this->markupStream = new MarkupStream($markup);

    $this->renderAll($app, $this->markupStream);
  }
}
