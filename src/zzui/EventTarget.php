<?php

namespace zzui;

use zzui\markup\html\Page;

interface EventTarget
{
  public function processEvents(Context $app, Page $page);
}
