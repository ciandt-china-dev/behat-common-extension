<?php

namespace Ciandt\Behat\EnvironmentExtension\Gherkin;

use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser as GherkinParser;
use Ciandt\Behat\EnvironmentExtension\EnvironmentRepository;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class Parser.
 *
 * @package Ciandt\Behat\EnvironmentExtension\Gherkin
 */
class Parser extends GherkinParser {

  /**
   * The gherkin parser.
   *
   * @var GherkinParser
   */
  protected $subject;

  /**
   * The environment repository.
   *
   * @var EnvironmentRepository
   */
  protected $environmentRepository;

  /**
   * The base path.
   *
   * @var string
   */
  protected $basePath;

  /**
   * The default suite path.
   *
   * @var array
   */
  protected $defaultSuitePath;

  /**
   * Parser constructor.
   *
   * @param GherkinParser $subject
   * @param Lexer $lexer
   * @param EnvironmentRepository $environment_repository
   * @param string $base_path
   * @param array $default_suite
   */
  public function __construct(GherkinParser $subject, Lexer $lexer, EnvironmentRepository $environment_repository, string $base_path, array $default_suite) {
    parent::__construct($lexer);
    $this->subject = $subject;
    $this->environmentRepository = $environment_repository;
    $this->basePath = $base_path;
    $this->defaultSuitePath = $default_suite['paths'][0] ?? $this->basePath . '/features';
  }

  /**
   * {@inheritdoc}
   */
  public function parse($input, $file = null) {
    $feature = $this->subject->parse($input, $file);

    if (!$feature) {
      return $feature;
    }

    $tags = $feature->getTags();

    // Enable environment tags follow directory.
    if ($this->environmentRepository->isTagsFollowDirectory()) {
      $relative_path = UrlGenerator::getRelativePath(
        rtrim($this->defaultSuitePath ?: $this->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
        $file
      );

      $relative_path_exploder = array_unique(array_filter(explode(DIRECTORY_SEPARATOR, $relative_path)));

      $tags = array_merge(
        $tags,
        array_intersect($this->environmentRepository->getEnvironments(), $relative_path_exploder)
      );

    }

    // Put default tags.
    if ($this->environmentRepository->getDefaultTags()) {
      $tags = array_merge($tags, $this->environmentRepository->getDefaultTags());
    }

    // If no environment tag in tags, put default environment into tag.
    if (empty(array_intersect($tags, $this->environmentRepository->getEnvironments()))) {
      $tags = array_merge($tags, [$this->environmentRepository->getDefaultEnvironment()]);
    }

    return new FeatureNode(
      $feature->getTitle(),
      $feature->getDescription(),
      array_filter(array_unique($tags)),
      $feature->getBackground(),
      $feature->getScenarios(),
      $feature->getKeyword(),
      $feature->getLanguage(),
      $feature->getFile(),
      $feature->getLine()
    );
  }

}
