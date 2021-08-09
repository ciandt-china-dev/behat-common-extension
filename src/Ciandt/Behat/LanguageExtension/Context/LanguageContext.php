<?php

namespace Ciandt\Behat\LanguageExtension\Context;

/**
 * Class LanguageContext.
 */
class LanguageContext extends RawLanguageContext {

  /**
   * Opens specified page
   * Example: Given I am on "http://batman.com" starting en language prefix
   * Example: And I am on "/articles/isBatmanBruceWayne" starting ja language prefix
   * Example: When I go to "/articles/isBatmanBruceWayne" starting de language prefix
   *
   * @Given /^(?:|I )am on "(?P<page>[^"]+)" starting (?P<langcode>[^ ]+) language prefix$/
   * @When /^(?:|I )go to "(?P<page>[^"]+)" starting (?P<langcode>[^ ]+) language prefix$/
   */
  public function visitStartinglanguagePrefix($page, $langcode) {
    $language_prefix = $this->getLanguagePrefixes()[$langcode] ?? '';

    if (!$language_prefix) {
      $this->visitPath($page);
      return;
    }

    [$host, $path] = $this->urlParser($page);

    // Follow the slash trailing rule.
    $normalized_page = urljoin($host, $this->pathStarting($path, $language_prefix));
    $normalized_page = substr($page, -strlen('/')) === '/' ? rtrim($normalized_page, '/') . '/' : rtrim($normalized_page, '/');

    $this->visitPath($normalized_page);
  }

  /**
   * Opens specified page
   * Example: Given I am on "http://batman.com" trailing en language prefix
   * Example: And I am on "/articles/isBatmanBruceWayne" trailing ja language prefix
   * Example: When I go to "/articles/isBatmanBruceWayne" trailing de language prefix
   *
   * @Given /^(?:|I )am on "(?P<page>[^"]+)" trailing (?P<langcode>[^ ]+) language prefix$/
   * @When /^(?:|I )go to "(?P<page>[^"]+)" trailing (?P<langcode>[^ ]+) language prefix$/
   */
  public function visitTrailingLanguagePrefix($page, $langcode) {
    $language_prefix = $this->getLanguagePrefixes()[$langcode] ?? '';

    if (!$language_prefix) {
      $this->visitPath($page);
      return;
    }

    [$host, $path] = $this->urlParser($page);

    // Follow the slash trailing rule.
    $normalized_page = urljoin($host, $this->pathTrailing($path, $language_prefix));
    $normalized_page = substr($page, -strlen('/')) === '/' ? rtrim($normalized_page, '/') . '/' : rtrim($normalized_page, '/');

    $this->visitPath($normalized_page);
  }

  /**
   * Checks, that current page PATH is equal to specified
   * Example: Then I should be on "/" starting en language prefix
   * Example: And I should be on "/bats" starting ja language prefix
   * Example: And I should be on "http://google.com" starting de language prefix
   *
   * @Then /^(?:|I )should be on "(?P<page>[^"]+)" starting (?P<langcode>[^ ]+) language prefix$/
   */
  public function assertPageAddressStartingLanguagePrefix($page, $langcode) {
    $language_prefix = $this->getLanguagePrefixes()[$langcode] ?? '';

    if (!$language_prefix) {
      $this->assertSession()->addressEquals($this->locatePath($page));
      return;
    }

    [$host, $path] = $this->urlParser($page);

    // Follow the slash trailing rule.
    $normalized_page = urljoin($host, $this->pathStarting($path, $language_prefix));
    $normalized_page = substr($page, -strlen('/')) === '/' ? rtrim($normalized_page, '/') . '/' : rtrim($normalized_page, '/');

    $this->assertSession()->addressEquals($this->locatePath($normalized_page));
  }

}
