<?php

namespace zzui\content\loader;

/**
 * File loader.
 */
class FileLoader implements Loader
{
  protected $resourcesPaths;

  public function __construct(array $resourcesPaths)
  {
    $this->resourcesPaths = $resourcesPaths;
  }

  /**
   * @see \zzui\resources\loader\Loader::supports()
   */
  public function supports($resource)
  {
    return true;
  }

  /**
   * @see \zzui\resources\loader\Loader::load()
   */
  public function load($resource)
  {
    foreach ($this->resourcesPaths as $resourcePath) {
      $file = $resourcePath . $resource;
      if (file_exists($file)) {
        return @file_get_contents($file);
      }
    }
  }
}
