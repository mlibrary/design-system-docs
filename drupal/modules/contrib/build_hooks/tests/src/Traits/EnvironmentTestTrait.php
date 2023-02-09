<?php

namespace Drupal\Tests\build_hooks\Traits;

use Drupal\build_hooks\Entity\FrontendEnvironment;
use Drupal\build_hooks\Trigger;

/**
 * Defines a trait for creating an environment.
 */
trait EnvironmentTestTrait {

  /**
   * Test environment.
   *
   * @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface
   */
  protected $environment;

  /**
   * Creates a test environment.
   */
  protected function createTestEnvironment() {
    $this->environment = FrontendEnvironment::create([
      'id' => 'foo',
      'label' => $this->randomMachineName(),
      'settings' => [],
      'plugin' => 'build_hooks_test',
      'deployment_strategy' => Trigger::DEPLOYMENT_STRATEGY_ENTITYSAVE,
    ]);
    $this->environment->save();
  }

}
