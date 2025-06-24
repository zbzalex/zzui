<?php

namespace zzui\http;

class Response
{
  protected $status;
  protected $headers;
  protected $body;
  protected $cookies;

  public function __construct($status = 200, $body = null, array $headers = [])
  {
    $this->status   = $status;
    $this->body     = $body;
    $this->headers  = $headers;
    $this->cookies  = [];
  }

  public function setStatus($status)
  {
    $this->status = $status;

    return $this;
  }

  public function setBody($body)
  {
    $this->body = $body;

    return $this;
  }

  public function clear()
  {
    $this->body = null;
  }

  public function write($text)
  {
    $this->body .= $text;
  }

  public function setHeaders(array $headers)
  {
    $this->headers = $headers;

    return $this;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function setHeader($header, $value)
  {
    $this->headers[$header] = $value;

    return $this;
  }

  public function addCookie(Cookie $cookie)
  {
    $this->cookies[] = $cookie;
  }

  public function send()
  {
    foreach ($this->headers as $header => $value) {
      header(sprintf('%s: %s', $header, $value));
    }

    echo $this->body;
  }
}
