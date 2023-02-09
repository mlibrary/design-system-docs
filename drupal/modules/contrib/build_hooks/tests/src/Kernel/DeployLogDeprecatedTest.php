<?php

namespace Drupal\Tests\build_hooks\Kernel;

/**
 * Defines a class for testing deploy log deprecations.
 *
 * @group build_hooks
 * @group legacy
 */
class DeployLogDeprecatedTest extends BuildHooksKernelTestBase {

  /**
   * Tests removed method.
   *
   * @expectedDeprecation Drupal\build_hooks\DeployLogger::getLogItemsSinceTimestamp is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. There is no replacement, instead work with the deployment content entity. See https://www.drupal.org/node/3172327
   */
  public function testDeprecatedMethod() {
    $logger = \Drupal::service('build_hooks.deploylogger');
    $logger->getLogItemsSinceTimestamp(0);
  }

}
