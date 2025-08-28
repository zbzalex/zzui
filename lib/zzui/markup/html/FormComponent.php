<?php

namespace zzui\markup\html;

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
