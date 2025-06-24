<?php

namespace miru\infrastructure\entities;

use zzui\orm\Entity;

class UserEntity extends Entity
{
  private $id;
  private $login;
  private $password;
}
