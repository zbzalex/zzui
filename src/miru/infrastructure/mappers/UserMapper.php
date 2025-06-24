<?php

namespace miru\infrastructure\mappers;

use miru\domain\entities\UserDomainEntity;

class UserMapper
{
  /**
   * @return \miru\domain\entities\UserDomainEntity
   */
  public static function fromArray(array $entity)
  {
    return new UserDomainEntity();
  }
}
