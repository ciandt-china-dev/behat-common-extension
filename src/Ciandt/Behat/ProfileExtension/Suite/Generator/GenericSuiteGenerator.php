<?php

namespace Ciandt\Behat\ProfileExtension\Suite\Generator;

use Behat\Testwork\Suite\Generator\SuiteGenerator;
use Behat\Testwork\Suite\GenericSuite;
use function Rikudou\ArrayMergeRecursive\array_merge_recursive;

/**
 * Class GenericSuiteGenerator.
 *
 * @package Ciandt\Behat\ProfileExtension\Suite\Generator
 */
class GenericSuiteGenerator implements SuiteGenerator {

  /**
   * The subject.
   *
   * @var \Behat\Testwork\Suite\Generator\SuiteGenerator
   */
  protected $subject;

  /**
   * The default settings.
   *
   * @var array
   */
  protected $defaultSettings;

  /**
   * GenericSuiteGenerator constructor.
   *
   * @param \Behat\Testwork\Suite\Generator\SuiteGenerator $subject
   * @param array $default_settings
   */
  public function __construct(SuiteGenerator $subject, array $default_settings = []) {
    $this->subject = $subject;
    $this->defaultSettings = $default_settings;
  }

  /**
   * {@inheritdoc}
   */
  public function supportsTypeAndSettings($type, array $settings) {
    return $this->subject->supportsTypeAndSettings($type, $settings);
  }

  /**
   * {@inheritdoc}
   */
  public function generateSuite($suiteName, array $settings) {
    $suite = $this->subject->generateSuite($suiteName, $settings);

    // Add support for deep merge for suite settings, it's useful for
    // contexts, extensions, etc.
    $settings = $this->deepMergeDefaultSettings($suite->getSettings());

    // Add support to use wildcard in suite paths.
    if (isset($settings['paths'])) {
      $settings['paths'] = array_map(function ($path) {
        return strpos($path, '*') !== FALSE ? glob($path) : [$path];
      }, $settings['paths']);
      $settings['paths'] = array_merge(...$settings['paths']);
    }

    return new GenericSuite($suiteName, $settings);
  }

  /**
   * Deep merges provided settings into default ones.
   *
   * @param array $settings
   *
   * @return array
   */
  protected function deepMergeDefaultSettings(array $settings) {
    // TODO: This behavior will broken much cases, need more tests.
    // Example: it will merge default suite paths into profile suite paths.
    // return array_merge_recursive($this->defaultSettings, $settings);
    return $settings;
  }

}
