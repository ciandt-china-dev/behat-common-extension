<?php

namespace Ciandt\Behat\LanguageExtension;

use Ciandt\Behat\LanguageExtension\Traits\LanguageParametersTrait;

/**
 * Class LanguageRepository.
 *
 * @package Ciandt\Behat\LanguageExtension
 */
class LanguageRepository {

  use LanguageParametersTrait;

  /**
   * LanguageRepository constructor.
   *
   * @param array $parameters
   */
  public function __construct(array $parameters) {
    $this->setLanguageParameters($parameters);
  }

}
