<?php

namespace zzui;

use zzui\http\HttpRequest;

/**
 * @author zbzalex
 */
class CryptedUrlCoder implements UrlCoder
{
  public function __construct() {}

  /**
   * @see \zzui\UrlCoder::encode()
   */
  public function encode(RequestTarget $requestTarget)
  {
    if ($requestTarget instanceof ListenerRequestTarget) {

      $params = $requestTarget->getParams();

      unset($params['']);

      $params = array_merge(
        [
          '__zzui' => base64_encode('\\' . ltrim($requestTarget->getPageClass(), '\\')
            . ":"
            . $requestTarget->getComponentId()
            . ":"
            . '\\' . ltrim($requestTarget->getListener(), "\\")),
        ],
        $requestTarget->getParams()
      );

      return "/?" . urldecode(http_build_query($params));
    }

    return null;
  }

  /**
   * @see \zzui\UrlCoder::decode()
   */
  public function decode(HttpRequest $request)
  {
    $query = $request->getQuery();
    if (isset($query['__zzui'])) {
      $pageQuery  = is_string($query['__zzui']) ? base64_decode($query['__zzui']) : null;
      $segments   = explode(":", $pageQuery);
      if (count($segments) === 3) {
        $pageClass      = "\\" . ltrim($segments[0], "\\");
        $componentId    = preg_match("/^[\\0-9a-z]+$/i", $segments[1]) ? $segments[1] : null;
        $listener       = preg_match("/.+/i", $segments[2])
          ? "\\" . ltrim($segments[2], "\\")
          : null;
        
        unset($query['__zzui']);

        return new ListenerRequestTarget($pageClass, $componentId, $listener, $query);
      }
    }

    return null;
  }
}
