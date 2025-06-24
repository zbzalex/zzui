<?php

namespace zzui\markup\html;

interface FormComponent {
  public function updateModel($value);

  /**
   * @return array
   */
  public function validate();
}