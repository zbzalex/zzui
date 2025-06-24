<?php

namespace miru;

use Firebase\JWT\Key;
use miru\presentation\pages\HomePage;

class WebApplication extends \zzui\Application
{
  public function getHomePage()
  {
    return HomePage::class;
  }
  
  public function getJwtKey()
  {
    return new Key('secret', 'HS256');
  }  
}
