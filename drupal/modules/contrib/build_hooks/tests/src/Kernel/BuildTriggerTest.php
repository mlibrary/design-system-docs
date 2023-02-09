<?php

namespace Drupal\Tests\build_hooks\Kernel;

use Drupal\build_hooks\TriggerInterface;
use GuzzleHttp\Psr7\Response;

/**
 * Defines a class for testing plugins can prevent builds.
 *
 * @group build_hooks
 */
class BuildTriggerTest extends BuildHooksKernelTestBase {

  /**
   * Tests a plugin can prevent a build.
   */
  public function testPreventBuildViaPlugin() {
    $this->assertFrontendEnvironmentBuildHook(
      'build_hooks_test',
      TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE,
      NULL,
      ['whiz' => 'no deploy for you']
    );
  }

  /**
   * Tests a module can prevent a build.
   */
  public function testPreventBuildViaModule() {
    $this->assertFrontendEnvironmentBuildHook(
      'build_hooks_test',
      TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE,
      NULL,
      ['whiz' => 'no deploy for you module']
    );
  }

  /**
   * Tests a plugin can prevent a build via access to entities in the deploy.
   */
  public function testAccessToDeployContents() {
    $this->assertFrontendEnvironmentBuildHook(
      'build_hooks_test',
      TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE,
      NULL,
      ['whiz' => 'whang'],
      NULL,
      "can't let you do that dave"
    );
  }

  /**
   * Tests generic front-end environment deployments.
   *
   * @dataProvider providerFrontendEnvironment
   */
  public function testGenericFrontendEnvironment(string $deployment_strategy = TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL) {
    $this->assertFrontendEnvironmentBuildHook(
      'generic',
      $deployment_strategy,
      'http://example.com?foo=bar',
      [
        'build_hook_url' => 'http://example.com?foo=bar',
      ],
      new Response(201, [], 'Hello, Generic'));
  }

  /**
   * Provider for ::testFrontendEnvironment.
   *
   * @return array[]
   *   Test cases.
   */
  public function providerFrontendEnvironment() {
    return [
      'Entity save' => [
        TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE,
      ],
      'Cron' => [
        TriggerInterface::DEPLOYMENT_STRATEGY_CRON,
      ],
      'Manual' => [
        TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL,
      ],
    ];
  }

}
