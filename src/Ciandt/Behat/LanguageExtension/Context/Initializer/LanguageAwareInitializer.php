<?php

namespace Ciandt\Behat\LanguageExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Ciandt\Behat\LanguageExtension\Context\LanguageContextInterface;
use Ciandt\Behat\LanguageExtension\LanguageRepository;
use Ciandt\Behat\LanguageExtension\Traits\LanguageParametersTrait;

/**
 * Class LanguageAwareInitializer.
 *
 * @package Ciandt\Behat\LanguageExtension\Context\Initializer
 */
class LanguageAwareInitializer implements ContextInitializer {

  protected $languageRepository;

  /**
   * LanguageAwareInitializer constructor.
   *
   * @param LanguageRepository $language_repository
   */
  public function __construct(LanguageRepository $language_repository) {
    $this->languageRepository = $language_repository;
  }

  /**
   * {@inheritdoc}
   */
  public function initializeContext(Context $context) {
    // All contexts are passed here, only LanguageContextInterface is allowed.
    if (!$context instanceof LanguageContextInterface) {
      return;
    }

    $context->setLanguageParameters($this->languageRepository->getLanguageParameters());
  }

}
