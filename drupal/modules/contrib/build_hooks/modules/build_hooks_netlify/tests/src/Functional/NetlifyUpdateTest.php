<?php

namespace Drupal\Tests\build_hooks_netlify\Functional;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Defines a class for testing the update to the config entity.
 *
 * @group build_hooks
 */
class NetlifyUpdateTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../../../../tests/fixtures/update/d89-minimal-circleci-netlify.php.gz',
    ];
  }

  /**
   * Tests netlify_update_8201().
   *
   * @see netlify_update_8201()
   */
  public function testNetlifyConfigUpdate8201() {
    $this->assertTrue(\Drupal::service('config.storage')->exists('build_hooks_netlify.netlifyConfig'));
    $this->assertFalse(\Drupal::service('config.storage')->exists('build_hooks_netlify.settings'));

    // Run updates.
    $this->runUpdates();

    $this->assertFalse(\Drupal::service('config.storage')->exists('build_hooks_netlify.netlifyConfig'));
    $this->assertTrue(\Drupal::service('config.storage')->exists('build_hooks_netlify.settings'));

  }

}
