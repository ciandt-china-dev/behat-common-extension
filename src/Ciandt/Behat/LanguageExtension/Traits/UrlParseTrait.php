<?php

namespace Ciandt\Behat\LanguageExtension\Traits;

/**
 * Trait UrlParseTrait.
 *
 * @package Ciandt\Behat\LanguageExtension\Traits
 */
trait UrlParseTrait {

  /**
   * Url parser.
   *
   * @param string $url
   *   The url.
   *
   * @return array
   */
  protected function urlParser(string $url) {
    $url_parser = parse_url($url);

    // Prepare host.
    $host = '';

    if (isset($url_parser['scheme'])) {
      $host .= "{$url_parser['scheme']}:";
    }

    if (isset($url_parser['host'])) {
      $host .= "//{$url_parser['host']}";
    }

    // Prepare path.
    $path = $url_parser['path'] ?? '';

    return [$host, $path];
  }

  /**
   * Add starting for path.
   *
   * @param string $path
   *   The path.
   * @param string $starting
   *   The starting.
   *
   * @return string
   */
  protected function pathStarting(string $path, string $starting) {
    $starting_with_slash = substr($starting, -strlen('/')) === '/' ? $starting : "$starting/";
    if (
      trim($path, '/') !== trim($starting_with_slash, '/') &&
      substr(trim($path, '/'), 0, strlen($starting_with_slash)) !== $starting_with_slash
    ) {
      $path = $starting_with_slash . ltrim($path, '/');
    }
    return $path;
  }

  /**
   * Add trailing for path.
   *
   * @param string $path
   *   The path.
   * @param string $trailing
   *   The trailing.
   *
   * @return string
   */
  protected function pathTrailing(string $path, string $trailing) {
    $trailing_with_slash = substr($trailing, 0, strlen('/')) === '/' ? $trailing : "/$trailing";
    if (
      trim($path, '/') !== trim($trailing_with_slash, '/') &&
      substr(trim($path, '/'), -strlen($trailing_with_slash)) !== $trailing_with_slash
    ) {
      $path = rtrim($path, '/') . $trailing_with_slash;
    }
    return $path;
  }

}
