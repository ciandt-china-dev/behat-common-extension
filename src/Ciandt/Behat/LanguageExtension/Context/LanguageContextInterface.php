<?php

namespace Ciandt\Behat\LanguageExtension\Context;

/**
 * Interface LanguageContextInterface.
 *
 * @package Ciandt\Behat\LanguageExtension\Context
 */
interface LanguageContextInterface {

  /**
   * Set parameters provided for Language.
   *
   * @param array $parameters
   */
  public function setLanguageParameters(array $parameters);

}
