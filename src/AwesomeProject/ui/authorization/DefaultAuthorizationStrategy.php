<?php

namespace AwesomeProject\ui\authorization;

use AwesomeProject\ui\pages\HomePage;
use AwesomeProject\ui\pages\LoginPage;
use AwesomeProject\ui\pages\PasswordForgotPage;
use zzui\AuthorizationStrategy;
use zzui\Context;
use zzui\pages\AccessDeniedPage;

class DefaultAuthorizationStrategy implements AuthorizationStrategy
{
  public function isInstantiationAuthorized($pageClass, Context $ctx)
  {
    $user = $ctx->getRequest()->getSession()->getUser();
    $isLoggedIn = $user !== null;

    switch ($pageClass) {
      case HomePage::class:
      case LoginPage::class:
      case PasswordForgotPage::class:
        if ($isLoggedIn) {
          $ctx->setResponsePage(new AccessDeniedPage($ctx));
          return false;
        }
        break;
    }

    return true;
  }
}
