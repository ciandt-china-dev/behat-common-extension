<?php

namespace Ciandt\Behat\EnvironmentExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class EnvironmentExtension.
 *
 * @package Ciandt\Behat\EnvironmentExtension\ServiceContainer
 */
class EnvironmentExtension implements ExtensionInterface {

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'ciandt_environment_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder->
      children()->
        // Default environment.
        scalarNode('default_environment')->defaultValue('')->end()->
        // Environments.
        arrayNode('environments')->defaultValue([])->prototype('scalar')->end()->end()->
        // Tags settings.
        arrayNode('tags')->
          addDefaultsIfNotSet()->
          children()->
            booleanNode('follow_directory')->defaultValue(FALSE)->end()->
            arrayNode('default_tags')->defaultValue([])->prototype('scalar')->end()->end()->
          end()->
        end()->
        // Environment variables mapping.
        arrayNode('environment_variables_mapping')->defaultValue([])->prototype('scalar')->end()->end()->
      end()->
      validate()->
        ifTrue(function ($value) {
          $default_tags_diff = array_diff($value['tags']['default_tags'], $value['environments'] ?? []);
          if ($value['tags']['follow_directory'] && !empty($value['tags']['default_tags']) && !empty($default_tags_diff)) {
            return TRUE;
          }
        })->
        thenInvalid("Wrong default_tags or syntax error detected.\nExpected values should match environments")->
      end()->
    end();
  }

  /**
   * {@inheritdoc}
   */
  public function process(ContainerBuilder $container) {
  }

  /**
   * {@inheritdoc}
   */
  public function initialize(ExtensionManager $extensionManager) {
  }

  /**
   * {@inheritdoc}
   */
  public function load(ContainerBuilder $container, array $config) {
    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/config'));
    $loader->load('services.yml');

    $this->loadParameters($container, $config);
  }

  /**
   * Load test parameters.
   */
  private function loadParameters(ContainerBuilder $container, array $config) {
    // Store config in parameters array to be passed into the LanguageContext.
    $environment_parameters = [];
    foreach ($config as $key => $value) {
      $environment_parameters[$key] = $value;
    }
    $container->setParameter($this->getConfigKey() . '.parameters', $environment_parameters);
    $container->setParameter($this->getConfigKey() . '.default_environment', $config['default_environment']);

    // Get environment variables by mapping
    $environment_variables = [];
    foreach ($config['environment_variables_mapping'] as $key => $value) {
      $environment_variables[$key] = getenv($value);
    }
    $container->setParameter($this->getConfigKey() . '.environment_variables', $environment_variables);
  }

}
