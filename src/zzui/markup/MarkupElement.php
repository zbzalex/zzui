<?php

namespace zzui\markup;

/**
 * Base markup element.
 * 
 * @author zbzalex
 */
abstract class MarkupElement
{
  public function closes(ComponentTag $tag)
  {
    return false;
  }
}
