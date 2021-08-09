<?php

namespace Ciandt\Behat\LanguageExtension\Context;

use Behat\MinkExtension\Context\RawMinkContext;
use Ciandt\Behat\LanguageExtension\Traits\LanguageParametersTrait;
use Ciandt\Behat\LanguageExtension\Traits\UrlParseTrait;

/**
 * Class RawLanguageContext.
 *
 * @package Ciandt\Behat\LanguageExtension\Context
 */
class RawLanguageContext extends RawMinkContext implements LanguageContextInterface {

  use LanguageParametersTrait;
  use UrlParseTrait;

}
