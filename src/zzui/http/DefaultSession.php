<?php

namespace zzui\http;

class DefaultSession implements Session
{
  protected $id;
  protected $user;

  public function __construct($id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getUser()
  {
    return $this->user;
  }

  public function setUser($user)
  {
    $this->user = $user;
  }

  public function getFlash() {}

  public function get($key) {}
  public function set($key, $value) {}

  public function invalidate() {}
}
