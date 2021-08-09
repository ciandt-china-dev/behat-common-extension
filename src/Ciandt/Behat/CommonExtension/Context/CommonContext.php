<?php

namespace Ciandt\Behat\CommonExtension\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;

/**
 * Class CommonContext.
 */
class CommonContext extends RawMinkContext {

  /**
   *  The scope environment.
   *
   * @var \Behat\Behat\Context\Environment\InitializedContextEnvironment
   */
  protected $environment;

  /**
   * CommonContext constructor.
   */
  public function __construct() {
  }

  /**
   * Before scenario.
   *
   * @param BeforeScenarioScope $scope
   *   The scope.
   *
   * @BeforeScenario
   */
  public function beforeScenario(BeforeScenarioScope $scope) {
    // Load and save the environment for each scenario.
    $this->environment = $scope->getEnvironment();
  }

  /**
   * Opens specified page
   * Example: Given I am on "http://batman.com/" without slash trailing
   * Example: And I am on "/articles/isBatmanBruceWayne/" without slash trailing
   * Example: When I go to "/articles/isBatmanBruceWayne/" without slash trailing
   *
   * @Given /^(?:|I )am on "(?P<page>[^"]+)" without slash trailing$/
   * @When /^(?:|I )go to "(?P<page>[^"]+)" without slash trailing$/
   */
  public function visit($page) {
    $this->visitPath(rtrim($page, '/'));
  }

  /**
   * Checks, that current page PATH is equal to specified
   * Example: Then I should be on "/" with slash trailing
   * Example: And I should be on "/bats" with slash trailing
   * Example: And I should be on "http://google.com" with slash trailing
   *
   * @Then /^(?:|I )should be on "(?P<page>[^"]+)" with slash trailing$/
   */
  public function assertPageAddress($page) {
    $this->assertSession()->addressEquals($this->locatePath(rtrim($page, '/') . '/'));
  }

  /**
   * Checks, that current page has elements.
   *
   * Provide parameter data in the following format:
   *
   * | type | selector |
   * | css  | div.a    |
   *
   * @Then /^I should see the elements that matched :type :selector$/
   */
  public function assertElementExists(TableNode $table) {
    foreach ($table->getHash() as $hash) {
      $this->assertSession()->elementExists($hash['type'] ?? '', $hash['selector'] ?? '');
    }
  }

}
