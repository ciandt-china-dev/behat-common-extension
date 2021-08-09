<?php

namespace Ciandt\Behat\ProfileExtension;

use Ciandt\Behat\ProfileExtension\Traits\ProfileParametersTrait;

/**
 * Class ProfileRepository.
 *
 * @package Ciandt\Behat\ProfileExtension
 */
class ProfileRepository {

  use ProfileParametersTrait;

  /**
   * ProfileRepository constructor.
   *
   * @param array $parameters
   */
  public function __construct(array $parameters) {
    $this->setProfileParameters($parameters);
  }

}
