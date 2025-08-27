<?php

use PHPUnit\Framework\TestCase;
use zzui\markup\Markup;
use zzui\markup\MarkupParser;

class MarkupParserTest extends TestCase
{
    public function testParae()
    {
        $template = "<!doctype html>
        <html>
        <head><title>hello</title>
        </head><body>

        <div view-id=\"header\">[header placeholder]</div>
        
        <children />

        <div class=\"footer\">
            hello
        </div>
        
        </body>
        </html>";

        $elements = MarkupParser::parse($template);

        var_dump($elements);
    }
}
