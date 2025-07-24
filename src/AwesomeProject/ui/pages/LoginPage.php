<?php

namespace AwesomeProject\ui\pages;

use zzui\Context;
use zzui\markup\html\Form;
use zzui\markup\html\TextField;

class LoginPage extends Layout
{
  public function __construct(Context $app, array $params = [])
  {
    parent::__construct($app, $params);

    $usernameTextField = new TextField("username", null, [
      function ($value) {
        $value = trim($value);

        return empty($value)
          ? "Имя пользователя пустое"
          : null;
      }
    ]);

    $passwordTextField = new TextField("password", null);

    $form = new Form(
      "loginForm",
      function () use ($app, $usernameTextField) {
        //echo "form submitted";

        //var_dump($usernameTextField->getValue());

        $app->setResponsePage([
          HomePage::class,
          [
            'a' => 1,
          ],
        ]);
        $app->setRedirect(true);
      },
      function (array $errors) use($app) {
        // var_dump($errors);

        $app->setRedirect(true);
      }
    );

    $form->add($usernameTextField);
    $form->add($passwordTextField);

    $form->setVisible(false);

    $this->add($form);
  }
}
