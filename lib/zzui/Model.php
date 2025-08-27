<?php

namespace zzui;

/**
 * Component model.
 */
class Model
{
  private $object;

  public function __construct($object = null)
  {
    $this->object = $object;
  }

  public function getObject()
  {
    return $this->object;
  }

  public function setObject($object)
  {
    $this->object = $object;
  }

  public static function of($object = null)
  {
    return new Model($object);
  }
}
