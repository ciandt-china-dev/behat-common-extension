<?php

namespace Ciandt\Behat\LanguageExtension\ServiceContainer;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface;
use Behat\Testwork\ServiceContainer\ExtensionManager;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class LanguageExtension.
 *
 * @package Ciandt\Behat\LanguageExtension\ServiceContainer
 */
class LanguageExtension implements ExtensionInterface {

  const DEFAULT_LANGUAGE = 'en';

  const DEFAULT_LANGUAGES = [self::DEFAULT_LANGUAGE];

  const DEFAULT_LANGUAGE_PREFIXES = [self::DEFAULT_LANGUAGE => ''];

  /**
   * {@inheritdoc}
   */
  public function getConfigKey() {
    return 'ciandt_language_extension';
  }

  /**
   * {@inheritdoc}
   */
  public function configure(ArrayNodeDefinition $builder) {
    $builder->
      children()->
        // Default language.
        scalarNode('default_language')->defaultValue(self::DEFAULT_LANGUAGE)->end()->
        // Languages
        arrayNode('languages')->defaultValue(self::DEFAULT_LANGUAGES)->prototype('scalar')->end()->end()->
        // Language prefixes.
        arrayNode('language_prefixes')->defaultValue(self::DEFAULT_LANGUAGE_PREFIXES)->prototype('scalar')->end()->end()->
        // Tags settings.
        arrayNode('tags')->
          addDefaultsIfNotSet()->
          children()->
            booleanNode('follow_directory')->defaultValue(FALSE)->end()->
            arrayNode('default_tags')->defaultValue([])->prototype('scalar')->end()->end()->
          end()->
        end()->
      end()->
      validate()->
        ifTrue(function ($value) {
          $default_tags_diff = array_diff($value['tags']['default_tags'], $value['languages'] ?? []);
          if ($value['tags']['follow_directory'] && !empty($value['tags']['default_tags']) && !empty($default_tags_diff)) {
            return TRUE;
          }
        })->
        thenInvalid("Wrong default_tags or syntax error detected.\nExpected values should match languages")->
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
