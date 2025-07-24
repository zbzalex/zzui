<?php

namespace zzui;

interface AuthorizationStrategy
{
  public function isInstantiationAuthorized($pageClass, Context $ctx);
}
