<?php

namespace zzui\markup\html;

/**
 * @author zbzalex
 */
interface FormComponent
{
  /**
   * @param mixed $value
   */
  public function updateModel($value);

  /**
   * @return array
   */
  public function validate();
}
