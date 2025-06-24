<?php

namespace zzui;

/**
 * @author zbzalex
 */
interface RequestTarget
{
  /**
   * @param \zzui\Context the application
   */
  public function respond(Context $app);
}
