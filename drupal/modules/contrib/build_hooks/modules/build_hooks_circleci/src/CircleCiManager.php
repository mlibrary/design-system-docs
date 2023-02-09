<?php

namespace Drupal\build_hooks_circleci;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use GuzzleHttp\ClientInterface;

/**
 * Defines a service for managing Circle CI deployments.
 */
class CircleCiManager implements CircleCiManagerInterface {

  const CIRCLE_CI_BASE_PATH = 'https://circleci.com/api/v1.1';
  const CIRCLE_CI_HOSTED_PLATFORM = 'github';
  const CIRCLE_CI_DATE_FORMAT = 'Y-m-d\TH:i:s+';

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;

  /**
   * The Date formatter service.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * Constructs a new CircleCiManager object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client, DateFormatterInterface $date_formatter) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Build the url to trigger a circle ci build depending on the environment.
   *
   * @param array $config
   *   The plugin configuration array.
   *
   * @return string
   *   The url to call to trigger a deployment in the environment.
   */
  private function buildCircleCiApiBuildUrlForEnvironment(array $config) {
    $circleCiConf = $this->configFactory->get('build_hooks_circleci.settings');
    $apiKey = $circleCiConf->get('circleci_api_key');
    return $this->buildCircleciApiBasePathForEnvironment($config) . "build?circle-token=$apiKey";
  }

  /**
   * Build a url to call circle ci depending on the frontend environment config.
   *
   * @param array $config
   *   The configuration array from the plugin.
   *
   * @return string
   *   The url to call.
   */
  private function buildCircleCiApiBasePathForEnvironment(array $config) {
    $basePath = self::CIRCLE_CI_BASE_PATH;
    $platform = self::CIRCLE_CI_HOSTED_PLATFORM;
    $project = $config['project'];
    return "$basePath/project/$platform/$project/";
  }

  /**
   * Returns the build hooks details based on plugin configuration.
   *
   * @param array $config
   *   The plugin configuration array.
   *
   * @return \Drupal\build_hooks\BuildHookDetails
   *   Build hooks detail object with info about the request to make.
   */
  public function getBuildHookDetailsForPluginConfiguration(array $config) {
    $buildHookDetails = new BuildHookDetails();
    $buildHookDetails->setUrl($this->buildCircleCiApiBuildUrlForEnvironment($config));
    $buildHookDetails->setMethod('POST');
    $buildHookDetails->setOptions([
      'json' => [
        'branch' => $config['branch'],
      ],
    ]);
    return $buildHookDetails;
  }

  /**
   * Get the latest builds from Circle CI for and environment.
   *
   * @param array $settings
   *   The plugin settings array.
   * @param int $limit
   *   Number of desired builds to retrieve.
   *
   * @return array
   *   An array with info about the builds.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  public function retrieveLatestBuildsFromCircleciForEnvironment(array $settings, $limit = 1) {
    $url = $this->buildCircleCiApiRetrieveBuildsUrl($settings, $limit);
    $options = [
      'headers' => [
        'Accept' => 'application/json',
      ],
    ];
    $response = $this->httpClient->request('GET', $url, $options);
    $payload = json_decode($response->getBody()->getContents(), TRUE);
    return $payload;
  }

  /**
   * Build the url to retrieve latest builds from circle ci for an environment.
   *
   * @param array $config
   *   The configuration array from the plugin.
   * @param int $limit
   *   Number of desired builds to retrieve.
   *
   * @return string
   *   The url to call to get the builds.
   */
  private function buildCircleCiApiRetrieveBuildsUrl(array $config, $limit) {
    $circleCiConf = $this->configFactory->get('build_hooks_circleci.settings');
    $apiKey = $circleCiConf->get('circleci_api_key');
    $branch = $config['branch'];
    return $this->buildCircleCiApiBasePathForEnvironment($config) . "tree/$branch?circle-token=$apiKey&limit=$limit";
  }

  /**
   * Converts the datetime format into a drupal formatted date.
   *
   * @param string $datetime
   *   Date in the format returned by the CircleCi api.
   *
   * @return string
   *   Drupal formatted date.
   */
  public function formatCircleCiDateTime($datetime) {
    // Dates are in UTC format:
    $timezone = new \DateTimeZone('UTC');
    $date = \DateTime::createFromFormat(self::CIRCLE_CI_DATE_FORMAT, $datetime, $timezone);

    return $this->dateFormatter->format($date->getTimestamp(), 'long');
  }

}
