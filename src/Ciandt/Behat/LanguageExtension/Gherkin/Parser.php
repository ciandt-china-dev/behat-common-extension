<?php

namespace Ciandt\Behat\LanguageExtension\Gherkin;

use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser as GherkinParser;
use Ciandt\Behat\LanguageExtension\LanguageRepository;
use Ciandt\Behat\LanguageExtension\Traits\LanguageParametersTrait;
use Symfony\Component\Routing\Generator\UrlGenerator;

/**
 * Class Parser.
 *
 * @package Ciandt\Behat\LanguageExtension\Gherkin
 */
class Parser extends GherkinParser {

  /**
   * The gherkin parser.
   *
   * @var GherkinParser
   */
  protected $subject;

  /**
   * The language repository.
   *
   * @var LanguageRepository
   */
  protected $languageRepository;

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
   *   The subject.
   * @param \Behat\Gherkin\Lexer $lexer
   *   The lexer.
   * @param string $base_path
   *   The base path.
   */
  public function __construct(GherkinParser $subject, Lexer $lexer, LanguageRepository $language_repository, string $base_path, array $default_suite) {
    parent::__construct($lexer);
    $this->subject = $subject;
    $this->languageRepository = $language_repository;
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

    // Enable language tags follow directory.
    if ($this->languageRepository->isTagsFollowDirectory()) {
      $relative_path = UrlGenerator::getRelativePath(
        rtrim($this->defaultSuitePath ?: $this->basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR,
        $file
      );

      $relative_path_exploder = array_unique(array_filter(explode(DIRECTORY_SEPARATOR, $relative_path)));

      $tags = array_merge(
        $tags,
        array_intersect($this->languageRepository->getLanguages(), $relative_path_exploder)
      );
    }

    // Put default tags.
    if ($this->languageRepository->getDefaultTags()) {
      $tags = array_merge($tags, $this->languageRepository->getDefaultTags());
    }

    // If no language tag in tags, put default language into tag.
    if (empty(array_intersect($tags, $this->languageRepository->getLanguages()))) {
      $tags = array_merge($tags, [$this->languageRepository->getDefaultLanguage()]);
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
