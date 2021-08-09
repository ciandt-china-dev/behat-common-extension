<?php

namespace Ciandt\Behat\MinkExtension\Context;

use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\MinkExtension\Context\RawMinkContext;
use function Rikudou\ArrayMergeRecursive\array_merge_recursive;

/**
 * Defines application features from the specific context.
 */
class MinkOverrideContext implements Context {

  const MINK_OVERRIDE_KEY = 'mink_override';

  const MINK_OVERRIDE_PARAMETERS_PREFIX = self::MINK_OVERRIDE_KEY . '.parameters.';

  /**
   * The parameters.
   *
   * @var array
   */
  protected $parameters;

  /**
   *  The scope environment.
   *
   * @var \Behat\Behat\Context\Environment\InitializedContextEnvironment
   */
  protected $environment;

  /**
   * The mink contexts.
   *
   * @var \Behat\MinkExtension\Context\RawMinkContext[]
   */
  protected $minkContexts;

  /**
   * {@inheritdoc}
   */
  public function __construct($parameters = []) {
    $this->parameters = $parameters;
  }

  /**
   * The mink contexts.
   *
   * @return \Behat\MinkExtension\Context\RawMinkContext[]
   */
  protected function getMinkContexts() {
    if (!$this->minkContexts) {
      $this->minkContexts = array_filter($this->environment->getContexts(), function ($context) {
        return $context instanceof RawMinkContext;
      });
    }
    return $this->minkContexts;
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

    // Static cache for mink contexts.
    foreach ($this->getMinkContexts() as &$context) {
      $context->setMinkParameters(
        array_merge_recursive($context->getMinkParameters(), $this->parameters)
      );
    }

    // Override the mink parameters.
    foreach ($this->parameters as $name => $value) {
      $this->overrideMinkParameter($name, $value);
    }

    // Start basic auth.
    foreach ($this->getMinkContexts() as $context) {
      $this->startBasicAuth($context);
    }
  }

  /**
   * Restore the mink parameters.
   *
   * @AfterScenario
   */
  public function restoreMinkParameters() {
    $this->iRestoreTheMinkParameters();
  }

  /**
   * Override mink parameters.
   *
   * @param $name
   *   The name.
   *
   * @param $value
   *   The value.
   */
  protected function overrideMinkParameter($name, $value) {
    foreach ($this->getMinkContexts() as $context) {
      $mink_override_key = self::MINK_OVERRIDE_PARAMETERS_PREFIX . $name;
      if (!isset($context->getMinkParameters()[$mink_override_key])) {
        $context->setMinkParameter($mink_override_key, $context->getMinkParameter($name));
      }
      $context->setMinkParameter($name, $value);
    }
  }

  /**
   * Set basic auth for mink session.
   *
   * @see https://gist.github.com/jhedstrom/5bc5192d6dacbf8cc459#gistcomment-2739474
   */
  protected function startBasicAuth(RawMinkContext $context, $auth_user = NULL, $auth_pwd = NULL) {
    $session = $context->getSession();
    if ($session->getDriver() instanceof Selenium2Driver) {
      return;
    }
    if (!$session->isStarted()) {
      $session->start();
    }
    $session->setBasicAuth(
      $auth_user ?: $context->getMinkParameter('environment_variables.basic_auth_user'),
      $auth_pwd ?: $context->getMinkParameter('environment_variables.basic_auth_password')
    );
  }

  /**
   * I restore the mink parameters.
   *
   * @Given /^I restore the mink parameters$/
   */
  public function iRestoreTheMinkParameters() {
    foreach ($this->getMinkContexts() as $context) {
      foreach ($context->getMinkParameters() as $key => $parameter) {
        if (substr($key, 0, strlen(self::MINK_OVERRIDE_PARAMETERS_PREFIX)) !== self::MINK_OVERRIDE_PARAMETERS_PREFIX) {
          continue;
        }
        $context->setMinkParameter(substr($key, strlen(self::MINK_OVERRIDE_PARAMETERS_PREFIX)), $parameter);
      }
    }
  }

  /**
   * Override mink parameters at scenario runtime.
   *
   * Provide parameter data in the following format:
   *
   * | name     | value              |
   * | base_url | http://example.com |
   *
   * @Given /^I set the mink parameter :name to :value$/
   */
  public function iSetTheMinkParameterNameToValue(TableNode $table) {
    foreach ($table->getHash() as $hash) {
      if (empty($hash['name'])) {
        continue;
      }
      $this->overrideMinkParameter($hash['name'], $hash['value']);
    }
  }

  /**
   * Set the basic auth for mink session.
   *
   * Example: Then I set the basic auth with id tokiwa
   * Example: And I set the basic auth with id tokiwa
   * Example: And I set the basic auth with id tokiwa
   *
   * @Then /^(?:|I )set the basic auth with id "(?P<id>[^ "]+)"$/
   */
  public function iSetTheBasicAuthWithId(string $id) {
    foreach ($this->getMinkContexts() as $context) {
      // Prepare auth keys.
      $auth_keys = array_map('strtolower', [
        // ID_ENV.
        implode('_', [$id, $context->getMinkParameter('environment')]),
        // ID.
        implode('_', [$id]),
        // ENV.
        implode('_', [$context->getMinkParameter('environment')]),
        // BASIC.
        implode('_', ['basic']),
      ]);

      $auth_user = $auth_pwd = NULL;

      foreach ($auth_keys as $key) {
        if ($auth_user && $auth_pwd) {
          break;
        }
        $auth_user = $context->getMinkParameter("environment_variables.{$key}_auth_user");
        $auth_pwd = $context->getMinkParameter("environment_variables.{$key}_auth_password");
      }

      if (empty($auth_user) || empty($auth_pwd)) {
        continue;
      }

      $base_url = $context->getMinkParameter('base_url');
      $url_parser = parse_url($base_url);
      $url_parser['user'] = urlencode($auth_user);
      $url_parser['pass'] = urlencode($auth_pwd);
      $context->setMinkParameter('base_url', unparse_url($url_parser));
      $this->startBasicAuth($context, $auth_user, $auth_pwd);
    }
  }

}
