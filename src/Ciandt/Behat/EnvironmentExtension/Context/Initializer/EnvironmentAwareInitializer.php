<?php

namespace Ciandt\Behat\EnvironmentExtension\Context\Initializer;

use Behat\Behat\Context\Context;
use Behat\Behat\Context\Initializer\ContextInitializer;
use Behat\MinkExtension\Context\RawMinkContext;
use Ciandt\Behat\EnvironmentExtension\EnvironmentRepository;

/**
 * Class EnvironmentAwareInitializer.
 *
 * @package Ciandt\Behat\EnvironmentExtension\Context\Initializer
 */
class EnvironmentAwareInitializer implements ContextInitializer {

  /**
   * The environment repository.
   *
   * @var EnvironmentRepository
   */
  protected $environmentRepository;

  /**
   * EnvironmentAwareInitializer constructor.
   *
   * @param EnvironmentRepository $environment_repository
   */
  public function __construct(EnvironmentRepository $environment_repository) {
    $this->environmentRepository = $environment_repository;
  }

  /**
   * {@inheritdoc}
   */
  public function initializeContext(Context $context) {
    // All contexts are passed here, only EnvironmentContextInterface is allowed.
    if (!$context instanceof RawMinkContext) {
      return;
    }

    $context->setMinkParameter('environment', $this->environmentRepository->getDefaultEnvironment());

    foreach ($this->environmentRepository->getEnvironmentVariables() as $key => $value) {
      $context->setMinkParameter('environment_variables.' . $key, $value);
    }
  }

}
