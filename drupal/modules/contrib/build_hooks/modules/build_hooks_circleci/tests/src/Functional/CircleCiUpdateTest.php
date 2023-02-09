<?php

namespace Drupal\Tests\build_hooks_circleci\Functional;

use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Defines a class for testing the update to the config entity.
 *
 * @group build_hooks
 */
class CircleCiUpdateTest extends UpdatePathTestBase {

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
   * Tests circleci_update_8201().
   *
   * @see circleci_update_8201()
   */
  public function testCircleConfigUpdate8201() {
    $this->assertTrue(\Drupal::service('config.storage')->exists('build_hooks_circleci.circleCiConfig'));
    $this->assertFalse(\Drupal::service('config.storage')->exists('build_hooks_circleci.settings'));

    // Run updates.
    $this->runUpdates();

    $this->assertFalse(\Drupal::service('config.storage')->exists('build_hooks_circleci.circleCiConfig'));
    $this->assertTrue(\Drupal::service('config.storage')->exists('build_hooks_circleci.settings'));

  }

}
