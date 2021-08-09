<?php

namespace Ciandt\Behat\EnvironmentExtension;

use Ciandt\Behat\EnvironmentExtension\Traits\EnvironmentParametersTrait;

/**
 * Class EnvironmentRepository.
 *
 * @package Ciandt\Behat\EnvironmentExtension
 */
class EnvironmentRepository {

  use EnvironmentParametersTrait;

  /**
   * EnvironmentRepository constructor.
   *
   * @param string $default_environment
   * @param array $environment_variables
   * @param array $parameters
   */
  public function __construct(string $default_environment, array $environment_variables, array $parameters) {
    $this->setDefaultEnvironment($default_environment);
    $this->setEnvironmentParameters($parameters);
    $this->environmentVariables = $environment_variables;
  }

}
