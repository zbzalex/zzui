<?php

namespace zzui;

use zzui\http\HttpRequest;

/**
 * Default url coder.
 * 
 * @author zbzalex
 */
class DefaultUrlCoder implements UrlCoder
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
          '__zzui' => ltrim(str_replace("\\", ".", $requestTarget->getPageClass()), ".")
            . ":"
            . $requestTarget->getComponentId()
            . ":"
            . ltrim(str_replace("\\", ".", $requestTarget->getListener()), "."),
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
      $pageQuery  = is_string($query['__zzui']) ? $query['__zzui'] : null;
      $segments   = explode(":", $pageQuery);
      if (count($segments) === 3) {
        $pageClass      = "\\" . ltrim(str_replace(".", "\\", $segments[0]), "\\");
        $componentId    = preg_match("/^[0-9a-z]+$/i", $segments[1]) ? $segments[1] : null;
        $listener       = preg_match("/^[0-9a-z\.]+$/i", $segments[2])
          ? "\\" . ltrim(str_replace(".", "\\", $segments[2]), "\\")
          : null;
        
        unset($query['__zzui']);

        return new ListenerRequestTarget($pageClass, $componentId, $listener, $query);
      }
    }

    return null;
  }
}
