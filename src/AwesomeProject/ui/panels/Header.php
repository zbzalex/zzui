<?php

namespace AwesomeProject\ui\panels;

use zzui\markup\html\Label;
use zzui\markup\html\Panel;

class Header extends Panel {
    public function __construct($id)
    {
        parent::__construct($id);

        $label = new Label("logo", "AwesomeProject");
        $this->add($label);

    }
}
