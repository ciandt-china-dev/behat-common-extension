<?php

namespace Ciandt\Behat\EnvironmentExtension\Traits;

use Symfony\Component\Routing\Exception\InvalidParameterException;

/**
 * Trait EnvironmentParametersTrait.
 *
 * @package Ciandt
 */
trait EnvironmentParametersTrait {

  /**
   * Test parameters.
   *
   * @var array
   */
  protected $environmentParameters;

  /**
   * The default environment.
   *
   * @var string
   */
  protected $defaultEnvironment;

  /**
   * The environments.
   *
   * @var array
   */
  protected $environments;

  /**
   * Is tags follow directory?
   *
   * @var bool
   */
  protected $isTagsFollowDirectory;

  /**
   * The default tags.
   *
   * @var array
   */
  protected $defaultTags;

  /**
   * The environment variables.
   *
   * @var array
   */
  protected $environmentVariables;

  /**
   * Set parameters provided for Environment.
   *
   * @param array $parameters
   *   The parameters to set.
   *
   * @return $this
   */
  public function setEnvironmentParameters(array $parameters) {
    $this->environmentParameters = $parameters;
    return $this;
  }

  /**
   * Get the environment parameters.
   *
   * @return array
   */
  public function getEnvironmentParameters() {
    return $this->environmentParameters;
  }

  /**
   * Returns a specific Environment parameter.
   *
   * @param string $name
   *   Parameter name.
   *
   * @return mixed
   *   The value.
   */
  public function getEnvironmentParameter($name) {
    return isset($this->environmentParameters[$name]) ? $this->environmentParameters[$name] : null;
  }

  /**
   * Set the default environment.
   *
   * @param string $environment
   *   The environment.
   *
   * @return $this
   */
  public function setDefaultEnvironment(string $environment) {
    $this->defaultEnvironment = $environment;
    return $this;
  }

  /**
   * Get the default environment.
   *
   * @return string
   */
  public function getDefaultEnvironment() {
    if (!is_string($this->defaultEnvironment)) {
      $this->defaultEnvironment = (string) $this->getEnvironmentParameter('default_environment') ?: '';
    }
    return $this->defaultEnvironment;
  }

  /**
   * Set the environments.
   *
   * @param array $environments
   *   The environments.
   *
   * @return $this
   */
  public function setEnvironments(array $environments) {
    $this->environments = $environments;
    return $this;
  }

  /**
   * Get the environments.
   *
   * @return array
   */
  public function getEnvironments() {
    if (!is_array($this->environments)) {
      $this->environments = (array) $this->getEnvironmentParameter('environments') ?: [];
    }
    return $this->environments;
  }

  /**
   * Set is tags follow directory.
   *
   * @param bool $is
   *   The is or not.
   * @return $this
   */
  public function setIsTagsFollowDirectory(bool $is) {
    $this->isTagsFollowDirectory = $is;
    return $this;
  }

  /**
   * Is the tags follow directory ?
   *
   * @return bool
   */
  public function isTagsFollowDirectory() {
    if (!is_bool($this->isTagsFollowDirectory)) {
      $this->isTagsFollowDirectory = (bool) $this->getEnvironmentParameter('tags')['follow_directory'] ?? FALSE;
    }
    return $this->isTagsFollowDirectory;
  }

  /**
   * Set the default tags.
   *
   * @param array $tags
   *   The tags.
   *
   * @return $this
   */
  public function setDefaultTags(array $tags) {
    $this->defaultTags = $tags;
    return $this;
  }

  /**
   * @return array
   */
  public function getDefaultTags() {
    if (!is_array($this->defaultTags)) {
      $this->defaultTags = (array) $this->getEnvironmentParameter('tags')['default_tags'] ?? [];
    }
    return $this->defaultTags;
  }

  /**
   * Get environment variables.
   *
   * @return array
   */
  public function getEnvironmentVariables() {
    if (!is_array($this->environmentVariables)) {
      throw new InvalidParameterException('No environment variables');
    }
    return $this->environmentVariables;
  }

}
