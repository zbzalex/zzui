<?php

namespace miru\presentation\authorization;

use miru\presentation\pages\HomePage;
use miru\presentation\pages\LoginPage;
use miru\presentation\pages\PasswordForgotPage;
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
