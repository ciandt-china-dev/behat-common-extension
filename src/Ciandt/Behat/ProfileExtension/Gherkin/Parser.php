<?php

namespace Ciandt\Behat\ProfileExtension\Gherkin;

use Behat\Gherkin\Lexer;
use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Parser as GherkinParser;
use Ciandt\Behat\ProfileExtension\ProfileRepository;

/**
 * Class Parser.
 *
 * @package Ciandt\Behat\ProfileExtension\Gherkin
 */
class Parser extends GherkinParser {

  /**
   * The gherkin parser.
   *
   * @var GherkinParser
   */
  protected $subject;

  /**
   * The profile repository.
   *
   * @var ProfileRepository
   */
  protected $profileRepository;

  /**
   * Parser constructor.
   *
   * @param GherkinParser $subject
   * @param Lexer $lexer
   * @param ProfileRepository $profile_repository
   */
  public function __construct(GherkinParser $subject, Lexer $lexer, ProfileRepository $profile_repository) {
    parent::__construct($lexer);
    $this->subject = $subject;
    $this->profileRepository = $profile_repository;
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

    if ($this->profileRepository->getName()) {
      $tags[] = $this->profileRepository->getName();
    }

    if ($this->profileRepository->getDefaultTags()) {
      $tags = array_merge($tags, $this->profileRepository->getDefaultTags());
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
