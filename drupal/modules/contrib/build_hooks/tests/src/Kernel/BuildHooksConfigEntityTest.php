<?php

namespace Drupal\Tests\build_hooks\Kernel;

use Drupal\build_hooks\TriggerInterface;

/**
 * Tests behaviour of the config-entity + plugin system.
 *
 * @group build_hooks
 */
class BuildHooksConfigEntityTest extends BuildHooksKernelTestBase {

  /**
   * Tests front-end environment deployments.
   *
   * @dataProvider providerFrontendEnvironment
   */
  public function testFrontendEnvironment(string $deployment_strategy = TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE) {
    $this->assertFrontendEnvironmentBuildHook('build_hooks_test', $deployment_strategy, 'http://example.com?whiz=bar', [
      'whiz' => 'bar',
    ]);
  }

  /**
   * Provider for ::testFrontendEnvironment.
   *
   * @return array[]
   *   Test cases.
   */
  public function providerFrontendEnvironment() {
    return [
      'Entity save' => [],
      'Cron' => [
        TriggerInterface::DEPLOYMENT_STRATEGY_CRON,
      ],
      'Manual' => [
        TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL,
      ],
    ];
  }

}
