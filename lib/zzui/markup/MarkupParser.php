<?php

namespace zzui\markup;

class MarkupParser
{
  /**
   * @var string
   */
  protected $input;

  /**
   * @var int
   */
  protected $inputPosition = 0;

  /**
   * @var int
   */
  protected $len = 0;

  public static $instance = null;

  /**
   * Constructor.
   */
  public function __construct() {}

  /**
   * @param string $input
   */
  public function setInput($input)
  {
    $this->input = $input;
    $this->inputPosition = 0;
    if (!extension_loaded("mbstring")) {
      throw new \RuntimeException("mbstring extensin required");
    }

    $this->len = mb_strlen($this->input);
  }

  /**
   * @return \zzui\markup\MarkupElement|null
   */
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

  /**
   * @return \zzui\markup\MarkupElement
   */
  public function parseTagText($tagText)
  {
    $tag = new MarkupElement();

    $matches = [];

    preg_match('/^([^\s]+)(.*)$/s', $tagText, $matches);
    if (! $matches || count($matches) === 0) {
      return null;
    }

    $tag->name = $matches[1];
    $attributes = [];

    preg_match_all(
      '/([a-z_\-@][a-z0-9_\-]+)(?:\s*=\s*(["\'])(.*?)\2)?/',
      trim($matches[2]),
      $matches2,
      PREG_SET_ORDER
    );

    foreach ($matches2 as $attr) {
      $key = $attr[1];
      $value = isset($attr[3]) ? $attr[3] : null;
      $attributes[$key] = $value;
    }

    $tag->attributes = $attributes;

    return $tag;
  }

  public function parseMarkup($markup)
  {
    $this->setInput($markup);


    /** @var \zzui\markup\MarkupElement[] */
    $elements = [];

    /** @var \zzui\markup\MarkupElement[] */
    $tags = [];

    /** @var int $pos */
    $pos = 0;

    /** @var bool $addTag */
    $addTag = false;

    /** @var \zzui\markup\MarkupElement|null $tag */
    $tag = null;

    while (
      $this->inputPosition <= $this->len
      && ($tag = $this->nextTag()) !== null
    ) {

      if ($tag->type === 'open') {
        $tags[] = $tag;

        $addTag = isset($tag->attributes['@id']);
      } else if ($tag->type === 'close') {

        if (count($tags) === 0) {
          throw new \Exception(
            "Close tag \"" . $tag->name . "\" has no open tag."
          );
        }

        /** @var \zzui\markup\MarkupElement $top */
        $top = array_pop($tags);

        $mismatch = $top->name !== $tag->name;

        if ($mismatch) {
          while ($mismatch && !$top->requiresCloseTag()) {
            $top = array_pop($tags);
            $mismatch = $top->name !== $tag->name;
          }

          if ($mismatch) {
            throw new \Exception(
              "Close tag \"" . $tag->name . "\" has no open tag."
            );
          }
        }

        $tag->closes = $top;
        $addTag = isset($top->attributes['@id']);
      } else if ($tag->type === 'open_close') {
        $tag->closes = $tag;
        $addTag = isset($tag->attributes['@id']) || $tag->name === 'children';
      }

      if ($addTag !== false) {

        if ($tag->pos > $pos) {
          $elements[] = new RawMarkup(mb_substr($this->input, $pos, $tag->pos - $pos));
        }

        $elements[] = $tag;

        $pos = $tag->pos + $tag->len;
      }
    }

    while (count($tags) > 0) {

      /** @var \zzui\markup\MarkupElement $tag */
      $tag = $tags[count($tags) - 1];

      if (! $tag->requiresCloseTag()) {
        array_pop($tags);
      } else {
        throw new \RuntimeException(
          "Unknown open tag \"" . $tag . "\""
        );
      }
    }

    if ($pos < $this->len) {
      $elements[] = new RawMarkup(mb_substr($this->input, $pos, $this->len));
    }

    return new Markup($elements);
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
