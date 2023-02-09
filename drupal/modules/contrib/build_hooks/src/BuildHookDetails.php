<?php

namespace Drupal\build_hooks;

/**
 * Class BuildHookDetails.
 *
 * Holds information to make the call to an external service for a build hook.
 */
class BuildHookDetails {

  /**
   * The url to call.
   *
   * @var string
   */
  protected $url = '';

  /**
   * The method to use (POST,GET,...)
   *
   * @var string
   */
  protected $method = '';

  /**
   * The options of the request.
   *
   * @var array
   */
  protected $options = [];

  /**
   * Get the url.
   *
   * @return string
   *   The url.
   */
  public function getUrl(): string {
    return $this->url;
  }

  /**
   * Set the url.
   *
   * @param string $url
   *   The url.
   */
  public function setUrl(string $url) {
    $this->url = $url;
  }

  /**
   * Get the method.
   *
   * @return string
   *   The method.
   */
  public function getMethod(): string {
    return $this->method;
  }

  /**
   * Set the method.
   *
   * @param string $method
   *   The method.
   */
  public function setMethod(string $method) {
    $this->method = $method;
  }

  /**
   * Get the body.
   *
   * @return array
   *   The body.
   *
   * @deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0.
   *   The getBody() method is depcrecated. Use getOptions() method instead.
   *
   * @see https://www.drupal.org/node/3173753
   */
  public function getBody(): array {
    @trigger_error(__METHOD__ . ' is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. Instead, you should use Drupal\build_hooks\BuildHookDetails::getOptions. See https://www.drupal.org/node/3173753', E_USER_DEPRECATED);
    return $this->options;
  }

  /**
   * Get the options.
   *
   * @return array
   *   The options.
   */
  public function getOptions(): array {
    return $this->options;
  }

  /**
   * Set the body.
   *
   * @param array $body
   *   The array.
   *
   * @deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0.
   *   The setBody() method is depcrecated. Use setOptions() method instead.
   *
   * @see https://www.drupal.org/node/3173753
   */
  public function setBody(array $body) {
    @trigger_error(__METHOD__ . ' is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. Instead, you should use Drupal\build_hooks\BuildHookDetails::setOptions. See https://www.drupal.org/node/3173753', E_USER_DEPRECATED);
    $this->options = $body;
  }

  /**
   * Set the options.
   *
   * @param array $options
   *   The array.
   */
  public function setOptions(array $options) {
    $this->options = $options;
  }

}
