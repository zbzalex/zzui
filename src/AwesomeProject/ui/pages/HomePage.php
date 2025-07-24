<?php

namespace AwesomeProject\ui\pages;

use AwesomeProject\ui\pages\LoginPage;
use zzui\markup\html\Label;
use zzui\Context;
use zzui\markup\html\Link;
use zzui\markup\html\Page;
use zzui\markup\html\PageLink;

class HomePage extends Layout
{
  public function __construct(
    Context $ctx,
    array $params = []
  ) {
    parent::__construct($ctx, $params);

    $user       = $ctx->getRequest()->getSession()->getUser();
    $isLoggedIn = $user !== null;

    $label = new Label("welcomeText", "hello");
    $this->add($label);

    $this->add(new PageLink("loginPageLink", LoginPage::class));
    
    $this->add(new Link("loginPageLink2", function () use ($ctx, $label) {
      $label->setValue("hello2");
      //$ctx->setRequestTarget(new RedirectRequestTarget("https://miru.mobi"));
    }));
        
  }
}
