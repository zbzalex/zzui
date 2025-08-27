<?php

namespace zzui;

/**
 * Default page factory class.
 */
class DefaultPageFactory implements PageFactory
{
  /**
   * @var \zzui\Context
   */
  protected $app;

  /**
   * Constructor.
   * 
   * @param \zzui\Context the application
   */
  public function __construct(Context $app)
  {
    $this->app = $app;
  }

  /**
   * @see \zzui\markup\html\Page
   */
  public function newPage($pageClass, array $params = [])
  {
    try {
      $reflectionClass = new \ReflectionClass($pageClass);

      $page = $reflectionClass->newInstanceArgs([
        $this->app,
        $params,
      ]);

      return $page;
    } catch (\ReflectionException $e) {
    }

    return null;
  }
}
