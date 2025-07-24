<?php

namespace zzui\markup;

class MarkupParser
{
  protected $input;
  protected $inputPosition = 0;

  public static $instance = null;

  public function __construct() {}

  public function setInput($input)
  {
    $this->input = $input;
    $this->inputPosition = 0;
  }

  public function nextTag()
  {
    $openBracketIndex = mb_strpos($this->input, '<', $this->inputPosition);
    if ($openBracketIndex !== false) {
      $closeBracketIndex = mb_strpos($this->input, '>', $openBracketIndex);
      if ($closeBracketIndex === false) {
        throw new \Exception("no match closing bracket");
      }

      $tagText = mb_substr(
        $this->input,
        $openBracketIndex + 1,
        $closeBracketIndex - $openBracketIndex - 1
      );

      if (mb_substr($tagText, 0, 3) === '!--') {
        $this->inputPosition = mb_strpos($this->input, "-->", $openBracketIndex + 3);
        if ($this->inputPosition === false) {
          throw new \Exception('unclosed comment');
        }

        $this->inputPosition += 3;

        return $this->nextTag();
      } else {
        $type = 'open';

        if (mb_substr($tagText, -1) === '/') {
          $type = 'open_close';
          $tagText = mb_substr($tagText, 0, mb_strlen($tagText) - 1);
        } else if (mb_substr($tagText, 0, 1) === '/') {
          $type = 'close';
          $tagText = mb_substr($tagText, 1);
        }

        if (mb_substr($tagText, 0, 1) === '!' || mb_substr($tagText, 0, 1) === '?') {
          $this->inputPosition = $closeBracketIndex + 1;
          return $this->nextTag();
        } else {

          $tag = $this->parseTagText(trim($tagText));

          $tag->type = $type;
          $tag->text = mb_substr($this->input, $openBracketIndex, $closeBracketIndex + 1 - $openBracketIndex);
          $tag->pos  = $openBracketIndex;
          $tag->len  = $closeBracketIndex + 1 - $openBracketIndex;

          $this->inputPosition = $closeBracketIndex + 1;

          return $tag;
        }
      }
    }

    return null;
  }

  public function parseTagText($tagText)
  {
    $tag = new ComponentTag();

    preg_match('/^([^\s]+)(.*)$/s', $tagText, $matches);
    if (!$matches) {
      return null;
    }

    $tag->name = $matches[1];
    $attributesString = trim($matches[2]);
    $attributes = [];

    preg_match_all('/([a-z0-9_\-]+)(?:\s*=\s*(["\'])(.*?)\2)?/', $attributesString, $attrMatches, PREG_SET_ORDER);

    foreach ($attrMatches as $attr) {
      $key = $attr[1];
      $value = isset($attr[3]) ? $attr[3] : null;
      $attributes[$key] = $value;
    }

    $tag->id = isset($attributes['view-id']) ? $attributes['view-id'] : null;
    $tag->attributes = $attributes;

    return $tag;
  }

  public function parseMarkup($markup)
  {
    $list = [];
    $tags = [];
    $this->setInput($markup);
    $pos = 0;

    $addTag = false;

    $tag = null;

    $len = mb_strlen($this->input);

    while ($this->inputPosition <= $len && ($tag = $this->nextTag()) !== null) {
      if ($tag->type === 'open') {
        $tags[] = $tag;
        $addTag = $tag->id !== null;
      } else if ($tag->type === 'close') {
        if (count($tags) > 0) {
          $top = array_pop($tags);

          $mismatch = $top->name !== $tag->name;
          if ($mismatch) {
            while ($mismatch && !$top->requiresCloseTag()) {
              $top = array_pop($tags);
              $mismatch = $top->name !== $tag->name;
            }

            if ($mismatch) {
              throw new \Exception("closing tag expected for " . $tag->name);
            }
          }

          $tag->closes = $top;
          $addTag = $top->id !== null;
        } else {
          throw new \Exception("tag does not have matching open tag");
        }
      } else if ($tag->type === 'open_close') {
        $tag->closes = $tag;
        $addTag = $tag->id !== null || $tag->name == 'children';
      }

      if ($addTag) {
        if ($tag->pos > $pos) {
          $list[] = new RawMarkup(mb_substr($this->input, $pos, $tag->pos - $pos));
        }

        $list[] = $tag;
        $pos = $tag->pos + $tag->len;
      }
    }

    while (count($tags) > 0) {
      $tag = $tags[count($tags) - 1];
      if (!$tag->requiresCloseTag()) {
        array_pop($tags);
      } else {
        throw new \Exception("tag did not have a close tag");
      }
    }

    if ($pos < $len) {
      $list[] = new RawMarkup(mb_substr($this->input, $pos, $len));
    }

    return $list;
  }

  public static function getInstance()
  {
    if (MarkupParser::$instance === null) {
      MarkupParser::$instance = new MarkupParser();
    }

    return MarkupParser::$instance;
  }

  public static function parse($content)
  {
    $parser = MarkupParser::getInstance();
    return $parser->parseMarkup($content);
  }
}
