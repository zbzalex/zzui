<?php

namespace zzui\content\loader;

/**
 * Loader interface.
 */
interface Loader
{
  /**
   * Check resource support
   */
  public function supports($resource);

  /**
   * Load an resource.
   */
  public function load($resource);
}
