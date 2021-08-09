<?php

namespace Ciandt\Behat\LanguageExtension\Traits;

use Ciandt\Behat\LanguageExtension\ServiceContainer\LanguageExtension;

/**
 * Trait LanguageParametersTrait.
 *
 * @package Ciandt
 */
trait LanguageParametersTrait {

  /**
   * Test parameters.
   *
   * @var array
   */
  protected $languageParameters;

  /**
   * The default language.
   *
   * @var string
   */
  protected $defaultLanguage;

  /**
   * The languages.
   *
   * @var array
   */
  protected $languages;

  /**
   * The language prefixes.
   *
   * @var array
   */
  protected $languagePrefixes;

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
   * Set parameters provided for Language.
   *
   * @param array $parameters
   *   The parameters to set.
   *
   * @return $this
   */
  public function setLanguageParameters(array $parameters) {
    $this->languageParameters = $parameters;
    return $this;
  }

  /**
   * Get language parameters.
   *
   * @return array
   */
  public function getLanguageParameters() {
    return $this->languageParameters;
  }

  /**
   * Returns a specific Language parameter.
   *
   * @param string $name
   *   Parameter name.
   *
   * @return mixed
   *   The value.
   */
  public function getLanguageParameter($name) {
    return isset($this->languageParameters[$name]) ? $this->languageParameters[$name] : NULL;
  }

  /**
   * Set the default language.
   *
   * @param string $default_language
   *
   * @return $this
   */
  public function setDefaultLanguage(string $default_language) {
    $this->defaultLanguage = $default_language;
    return $this;
  }

  /**
   * Get the default language.
   *
   * @return string
   */
  public function getDefaultLanguage() {
    if (!is_string($this->defaultLanguage)) {
      $this->defaultLanguage = (string) $this->getLanguageParameter('default_language') ?: LanguageExtension::DEFAULT_LANGUAGE;
    }
    return $this->defaultLanguage;
  }

  /**
   * Set the languages.
   *
   * @param array $languages
   *
   * @return $this
   */
  public function setLanguages(array $languages) {
    $this->languages = $languages;
    return $this;
  }

  /**
   * Get the languages.
   *
   * @return array
   */
  public function getLanguages() {
    if (!is_array($this->languages)) {
      $this->languages = (array) $this->getLanguageParameter('languages') ?: LanguageExtension::DEFAULT_LANGUAGES;
    }
    return $this->languages;
  }

  /**
   * Set the language prefixes.
   *
   * @param array $prefixes
   *
   * @return $this
   */
  public function setLanguagePrefixes(array $prefixes) {
    $this->languagePrefixes = $prefixes;
    return $this;
  }

  /**
   * Get the language prefixes.
   *
   * @return array
   */
  public function getLanguagePrefixes() {
    if (!is_array($this->languagePrefixes)) {
      $this->languagePrefixes = (array) $this->getLanguageParameter('language_prefixes') ?: LanguageExtension::DEFAULT_LANGUAGE_PREFIXES;
    }
    return $this->languagePrefixes;
  }

  /**
   * Set is tags follow directory.
   *
   * @param bool $is
   *
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
      $this->isTagsFollowDirectory = (bool) $this->getLanguageParameter('tags')['follow_directory'] ?? FALSE;
    }
    return $this->isTagsFollowDirectory;
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
   * @return array
   */
  public function getDefaultTags() {
    if (!is_array($this->defaultTags)) {
      $this->defaultTags = (array) $this->getLanguageParameter('tags')['default_tags'] ?? [];
    }
    return $this->defaultTags;
  }

}
