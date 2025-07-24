<?php

namespace zzui;

use zzui\http\HttpRequest;

/**
 * @author zbzalex
 */
class CryptedUrlCoder implements UrlCoder
{
  private $key;

  public function __construct(
    $key
  ) {
    $this->key = $key;
  }

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
          '__zzui' => $this->_encrypt('\\' . ltrim($requestTarget->getPageClass(), '\\')
            . ":"
            . $requestTarget->getComponentId()
            . ":"
            . '\\' . ltrim($requestTarget->getListener(), "\\"), $this->key),
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
      $pageQuery  = is_string($query['__zzui']) ? $this->_decrypt($query['__zzui'], $this->key) : null;
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

  private function _encrypt($plaintext, $key)
  {
    $key = hash('sha256', $key, true);
    $iv = openssl_random_pseudo_bytes(16);
    $ciphertext = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);

    return strtoupper(bin2hex($iv . $ciphertext));

    // return base64_encode($iv . $ciphertext);
  }

  private function _decrypt($encrypted, $key)
  {
    $key = hash('sha256', $key, true);

    //$data = base64_decode($encrypted);
    $data = hex2bin($encrypted);

    $iv = substr($data, 0, 16);
    $ciphertext = substr($data, 16);

    return openssl_decrypt($ciphertext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
  }
}
