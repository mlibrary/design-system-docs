<?php

namespace Drupal\build_hooks_circleci;

/**
 * CircleCiManager interface.
 */
interface CircleCiManagerInterface {

  /**
   * Returns the build hooks details based on plugin configuration.
   *
   * @param array $config
   *   The plugin configuration array.
   *
   * @return \Drupal\build_hooks\BuildHookDetails
   *   Build hooks detail object with info about the request to make.
   */
  public function getBuildHookDetailsForPluginConfiguration(array $config);

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
  public function retrieveLatestBuildsFromCircleciForEnvironment(array $settings, $limit = 1);

  /**
   * Converts the datetime format into a drupal formatted date.
   *
   * @param string $datetime
   *   Date in the format returned by the CircleCi api.
   *
   * @return string
   *   Drupal formatted date.
   */
  public function formatCircleCiDateTime($datetime);

}
