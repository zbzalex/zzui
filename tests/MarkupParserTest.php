<?php

use PHPUnit\Framework\TestCase;
use zzui\markup\Markup;
use zzui\markup\MarkupContainer;
use zzui\markup\MarkupParser;
use zzui\markup\MarkupStream;
use zzui\markup\RawMarkup;

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

    /** @var \zzui\markup\Markup $markup */
    $markup = MarkupParser::parse($template);

    // for (
    //   $i = 0, $ii = $markup->size();
    //   $i < $ii;
    //   $i++
    // ) {
    //   $el = $markup->get($i);
    //   echo $el->__toString() . "\n";
    // }

    $stream = new MarkupStream($markup);

    while ($stream->hasMore()) {
      $tag = $stream->get();
      if ($tag instanceof RawMarkup) {
        echo $tag->__toString() . "\n";
        $stream->next();
      } else {
        switch ($tag->type) {
          case 'open':
            
            $stream->next(); // skip opening tag
            $stream->skipToMatchCloseTag($tag);
            $stream->next(); // skip closing tag

            break;
          case 'open_close':

            if ($tag->name === 'children') {
              echo "children tag found\n";
            }

            $stream->next();

            break;
        }
      }
    }




  }
}
