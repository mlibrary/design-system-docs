<?php

namespace Drupal\build_hooks_netlify;

/**
 * NetlifyManager interface.
 */
interface NetlifyManagerInterface {

  /**
   * Converts the datetime format into a drupal formatted date.
   *
   * @param string $datetime
   *   Date in the format returned by the Netlify api.
   *
   * @return string
   *   Drupal formatted date.
   */
  public function formatNetlifyDateTime($datetime);

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
  public function retrieveLatestBuildsFromNetlifyForEnvironment(array $settings, $limit = 1);

}
