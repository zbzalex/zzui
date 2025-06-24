<?php

namespace zzui;

use zzui\http\Request;

/**
 * Url coder class.
 */
interface UrlCoder
{
    /**
     * Encode request target.
     */
    public function encode(RequestTarget $requestTarget);

    /**
     * Decode request and return request target.
     */
    public function decode(Request $request);
}
