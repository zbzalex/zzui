<?php

namespace AwesomeProject\ui\pages;

use AwesomeProject\ui\panels\Header;
use zzui\Context;
use zzui\markup\html\Label;
use zzui\markup\html\Page;

class Layout extends Page
{
    public function __construct(Context $app, array $params = [])
    {
        parent::__construct($app, $params);

        $this->add(new Header("header"));        
    }
}
