<?php

namespace zzui;

use zzui\http\FilterChain;
use zzui\http\HttpRequest;
use zzui\http\HttpResponse;

/**
 * Base application class should be extended.
 * 
 * @author zbzalex
 */
abstract class Application extends Context
{
  /**
   * Default applicatio page. Should be set a page class.
   * 
   * @return string page class
   * 
   * @see \zzui\markup\html\Page
   */
  public abstract function getHomePage();
}
