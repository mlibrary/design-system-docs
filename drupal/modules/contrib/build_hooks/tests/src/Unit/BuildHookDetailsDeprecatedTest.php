<?php

namespace Drupal\Tests\build_hooks\Unit;

use Drupal\Tests\UnitTestCase;
use Drupal\build_hooks\BuildHookDetails;

/**
 * Defines a class for testing BuildHookDetails deprecations.
 *
 * @group build_hooks
 * @group legacy
 */
class BuildHookDetailsDeprecatedTest extends UnitTestCase {

  /**
   * Tests deprecated methods.
   *
   * @expectedDeprecation Drupal\build_hooks\BuildHookDetails::getBody is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. Instead, you should use Drupal\build_hooks\BuildHookDetails::getOptions. See https://www.drupal.org/node/3173753
   * @expectedDeprecation Drupal\build_hooks\BuildHookDetails::setBody is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. Instead, you should use Drupal\build_hooks\BuildHookDetails::setOptions. See https://www.drupal.org/node/3173753
   */
  public function testDeprecatedMethods() {
    $details = new BuildHookDetails();
    $details->getBody();
    $details->setBody([]);
  }

}
