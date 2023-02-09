<?php

namespace Drupal\build_hooks_bitbucket;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use GuzzleHttp\ClientInterface;

/**
 * Service for managing BitBucket connection details.
 */
class BitbucketManager {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

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

  const API_PREFIX = "https://api.bitbucket.org/2.0/repositories/";

  const PIPELINES_PATH = "/pipelines/";

  const BITBUCKET_DATE_FORMAT = 'Y-m-d\TH:i:s+';

  /**
   * Constructs a BitbucketManager object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \GuzzleHttp\ClientInterface $http_client
   *   HTTP client.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
   *   The Date formatter service.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client, DateFormatterInterface $date_formatter) {
    $this->config = $config_factory->get('build_hooks_bitbucket.settings');
    $this->httpClient = $http_client;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Get the full URL for the pipelines api call.
   *
   * @param array $settings
   *   The configuration for this environment.
   *
   * @return string
   *   The api endpoint we should hit.
   */
  protected function getPipelinesApiPath(array $settings) {
    return self::API_PREFIX . $settings['repo']['workspace'] . '/' . $settings['repo']['slug'] . self::PIPELINES_PATH;
  }

  /**
   * Get the authentication headers for the request.
   *
   * @return array
   *   A list of username,password suitable for passing to guzzle.
   */
  protected function getAuth() {
    return [
      $this->config->get('username'),
      $this->config->get('password'),
    ];
  }

  /**
   * Converts hook configuration into an api call.
   *
   * @param array $settings
   *   The configuration for this hook.
   *
   * @return \Drupal\build_hooks\BuildHookDetails
   *   An object that will trigger a pipeline based on config.
   */
  public function getBuildHookDetailsForPluginConfiguration(array $settings) {
    $buildHookDetails = new BuildHookDetails();
    $buildHookDetails->setUrl($this->getPipelinesApiPath($settings));
    $buildHookDetails->setMethod('POST');
    $body = [
      'target' => [
        "type" => "pipeline_ref_target",
        "ref_name" => $settings['ref']['name'],
        "ref_type" => $settings['ref']['type'],
        "selector" => [
          "type" => $settings['selector']['type'],
          "pattern" => $settings['selector']['name'],
        ],
      ],
    ];
    $buildHookDetails->setOptions([
      'json' => $body,
      'auth' => $this->getAuth(),
    ]);
    return $buildHookDetails;
  }

  /**
   * Get the latest builds from bitbucket pipelines.
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
  public function retrieveLatestBuilds(array $settings, $limit = 10) {
    $url = $this->getPipelinesApiPath($settings);
    $url .= '?sort=' . urlencode('-created_on') . '&pagelen=' . ((int) $limit);

    if ($settings['ref']['type'] == 'branch') {
      $url .= '&target.branch=' . urlencode($settings['ref']['name']);
    }
    elseif ($settings['ref']['type'] == 'tag') {
      $url .= '&target.tag=' . urlencode($settings['ref']['name']);
    }

    $options = [
      'auth' => $this->getAuth(),
    ];

    $response = $this->httpClient->request('GET', $url, $options);
    return json_decode($response->getBody()->getContents(), TRUE);

  }

  /**
   * Converts the datetime format into a drupal formatted date.
   *
   * @param string $datetime
   *   Date in the format returned by the Bitbucket api.
   *
   * @return string
   *   Drupal formatted date.
   */
  public function formatBitbucketDateTime($datetime) {
    $timezone = new \DateTimeZone('UTC');
    $date = \DateTime::createFromFormat(self::BITBUCKET_DATE_FORMAT, $datetime, $timezone);

    return $this->dateFormatter->format($date->getTimestamp(), 'long');
  }

}
