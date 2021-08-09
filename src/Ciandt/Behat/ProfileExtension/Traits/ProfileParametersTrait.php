<?php

namespace Ciandt\Behat\ProfileExtension\Traits;

use Ciandt\Behat\ProfileExtension\ServiceContainer\ProfileExtension;

/**
 * Trait ProfileParametersTrait.
 *
 * @package Ciandt
 */
trait ProfileParametersTrait {

  /**
   * Test parameters.
   *
   * @var array
   */
  protected $profileParameters;

  /**
   * The default tags.
   *
   * @var array
   */
  protected $defaultTags;

  /**
   * Set parameters provided for Profile.
   *
   * @param array $parameters
   *   The parameters to set.
   */
  public function setProfileParameters(array $parameters) {
    $this->profileParameters = $parameters;
  }

  /**
   * Returns a specific Profile parameter.
   *
   * @param string $name
   *   Parameter name.
   *
   * @return mixed
   *   The value.
   */
  public function getProfileParameter($name) {
    return isset($this->profileParameters[$name]) ? $this->profileParameters[$name] : null;
  }

  /**
   * Get the tag name.
   *
   * @return string
   */
  public function getName() {
    return (string) $this->getProfileParameter('name') ?? '';
  }

  /**
   * Set the default tags.
   *
   * @param array $tags
   *
   * @return $this
   */
  public function setDefaultTags(array $tags) {
    $this->defaultTags = $tags;
    return $this;
  }

  /**
   * Get default tags.
   *
   * @return array
   */
  public function getDefaultTags() {
    if (!is_array($this->defaultTags)) {
      $this->defaultTags = (array) $this->getProfileParameter('tags')['default_tags'] ?? [];
    }
    return $this->defaultTags;
  }

}
