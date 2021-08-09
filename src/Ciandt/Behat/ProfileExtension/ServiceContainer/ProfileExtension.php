<?php

namespace Ciandt\Behat\ProfileExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ProfileExtension implements ExtensionInterface {

  const EXTENSION_NAME = 'ProfileExtension';

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'ciandt_profile_extension';
  }

  public function configure(ArrayNodeDefinition $builder) {
    $builder->
      children()->
        // Profile name.
        scalarNode('name')->defaultValue('')->end()->
        // Tag settings
        arrayNode('tags')->
          addDefaultsIfNotSet()->
          children()->
            arrayNode('default_tags')->defaultValue([])->prototype('scalar')->end()->end()->
          end()->
        end()->
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
    $language_parameters = [];
    foreach ($config as $key => $value) {
      $language_parameters[$key] = $value;
    }
    $container->setParameter($this->getConfigKey() . '.parameters', $language_parameters);
  }

}
