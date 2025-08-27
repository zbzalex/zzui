<?php

namespace zzui\http;

interface Session
{
  public function getId();

  public function getUser();
  public function setUser($user);

  public function getFlash();

  public function get($key);
  public function set($key, $value);
  public function invalidate();
}
