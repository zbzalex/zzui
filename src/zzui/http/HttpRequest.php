<?php

namespace zzui\http;

/**
 * @author zbzalex
 */
class HttpRequest
{
  protected $method;

  protected $uri;

  /**
   * @var array
   */
  protected $query;

  /**
   * @var array
   */
  protected $headers;

  /**
   * @var \zzui\http\Session
   */
  protected $session;

  protected $body;

  public function __construct(
    $method,
    $uri,
    array $query = [],
    array $headers = [],
    $body = null
  ) {
    $this->method = $method;
    $this->uri = $uri;
    $this->query = $query;
    $this->headers = $headers;
    $this->body = $body;
  }

  public static function createFromGlobals()
  {
    $headers = [];

    foreach ($_SERVER as $key => $value) {
      if (substr($key, 0, 5) == 'HTTP_') {
        $headers[strtolower(str_replace("_", "-", substr($key, 5)))] = $value;
      }
    }

    $request = new HttpRequest(
      $_SERVER['REQUEST_METHOD'],
      $_SERVER['REQUEST_URI'],
      $_GET,
      $headers,
      $_SERVER['REQUEST_METHOD'] === 'POST' ? $_POST : null
    );

    $request->setSession(new DefaultSession(session_id()));

    return $request;
  }

  public function setSession(Session $session)
  {
    $this->session = $session;
  }

  public function getSession()
  {
    return $this->session;
  }

  public function getMethod()
  {
    return $this->method;
  }

  public function getUri()
  {
    return $this->uri;
  }

  public function getQuery()
  {
    return $this->query;
  }

  public function getHeaders()
  {
    return $this->headers;
  }

  public function getBody()
  {
    return $this->body;
  }

  public function getCookies()
  {
    $header = isset($this->headers['cookie']) ? $this->headers['cookie'] : '';

    return array_map(
      function ($str) {

        $parts = explode('=', $str);

        return new Cookie(
          @$parts[0],
          @$parts[1]
        );
      },
      explode('; ', $header)
    );
  }
}
