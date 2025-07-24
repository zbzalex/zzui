<?php

namespace zzui;

class RedirectRequestTarget implements RequestTarget
{
    private $redirectUrl;

    public function __construct($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    public function respond(Context $ctx)
    {
        /** @var \zzui\http\Response $res */
        $res = $ctx->getResponse();

        $res->setHeader("Location", $this->redirectUrl);
    }
}
