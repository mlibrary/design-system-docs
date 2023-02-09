<?php

namespace Drupal\build_hooks_netlify;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Datetime\DateFormatterInterface;
use GuzzleHttp\ClientInterface;

/**
 * Defines a manager service for netlify deployments.
 */
class NetlifyManager implements NetlifyManagerInterface {

  const NETLIFY_BASE_PATH = 'https://api.netlify.com/api/v1/';
  const NETLIFY_DATE_FORMAT = 'Y-m-d\TH:i:s';

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
   * Constructs a new NetlifyManager object.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ClientInterface $http_client, DateFormatterInterface $date_formatter) {
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->dateFormatter = $date_formatter;
  }

  /**
   * Get the latest builds from netlify for and environment.
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
  public function retrieveLatestBuildsFromNetlifyForEnvironment(array $settings, $limit = 1) {
    $url = $this->buildNetlifyApiRetrieveBuildsUrl($settings);
    $options = [
      'headers' => [
        'Accept' => 'application/json',
      ],
    ];
    $response = $this->httpClient->request('GET', $url, $options);
    $payload = json_decode($response->getBody()->getContents(), TRUE);
    // Since there is no way in the api to filter by branch,
    // we do it by filtering the results:
    $results = array_filter($payload, $this->filterByBranch($settings['branch']));
    return array_slice($results, 0, $limit);
  }

  /**
   * Build the url to retrieve latest builds from netlify for an environment.
   *
   * @param array $config
   *   The configuration array from the plugin.
   *
   * @return string
   *   The url to call to get the builds.
   */
  private function buildNetlifyApiRetrieveBuildsUrl(array $config) {
    $netlifyConf = $this->configFactory->get('build_hooks_netlify.settings');
    $access_token = $netlifyConf->get('netlify_api_key');
    $api_id = $config['api_id'];
    return self::NETLIFY_BASE_PATH . "/sites/$api_id/deploys?access_token=$access_token";
  }

  /**
   * Converts the datetime format into a drupal formatted date.
   *
   * @param string $datetime
   *   Date in the format returned by the Netlify api.
   *
   * @return string
   *   Drupal formatted date.
   */
  public function formatNetlifyDateTime($datetime) {

    // We remove the last5 digits because we can't handle milliseconds:
    $datetime_no_millis = substr($datetime, 0, -5);

    $timezone = new \DateTimeZone('UTC');
    $date = \DateTime::createFromFormat(self::NETLIFY_DATE_FORMAT, $datetime_no_millis, $timezone);

    return $this->dateFormatter->format($date->getTimestamp(), 'long');
  }

  /**
   * Simple callback to filter an array by the branch property.
   *
   * @param string $branch
   *   The branch to filter for.
   *
   * @return \Closure
   *   Closure to be used in array_filter.
   */
  private function filterByBranch($branch) {
    return function ($item) use ($branch) {
      return $item['branch'] == $branch;
    };
  }

}
