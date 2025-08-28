<?php

namespace zzui\markup\html;

use zzui\markup\Component;

abstract class FormComponentImpl extends Component implements FormComponent
{
  /**
   * @var array
   */
  protected $validators;

  /**
   * @var mixed
   */
  protected $value;

  /**
   * Constructor.
   * 
   * @param string $id
   */
  public function __construct($id)
  {
    parent::__construct($id);
    $this->validators = [];
  }

  public function addValidator($validator)
  {
    $this->validators[] = $validator;
  }

  public function updateModel($value)
  {
    $this->value = $value;
  }

  public function getValue()
  {
    return $this->value;
  }

  public function validate()
  {
    $errors = [];

    foreach ($this->validators as $validator) {
      $message = call_user_func_array($validator, [$this->value]);
      if ($message !== null) {
        $errors[] = $message;
      }
    }

    return $errors;
  }
}
