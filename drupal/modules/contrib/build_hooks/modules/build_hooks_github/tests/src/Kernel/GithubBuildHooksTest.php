<?php

namespace Drupal\Tests\build_hooks_github\Kernel;

use Drupal\build_hooks\TriggerInterface;
use Drupal\Tests\build_hooks\Kernel\BuildHooksKernelTestBase;
use GuzzleHttp\Psr7\Response;

/**
 * Defines a test for the configuration form of the github workflow plugin.
 *
 * @group build_hooks_github
 */
class GithubBuildHooksTest extends BuildHooksKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'build_hooks',
    'build_hooks_github',
    'views',
    'build_hooks_test',
    'system',
    'user',
    'entity_test',
    'dynamic_entity_reference',
  ];

  /**
   * History of requests.
   *
   * @var \GuzzleHttp\Psr7\Request[]
   */
  protected $history = [];

  /**
   * Mock client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $mockClient;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $this->installConfig('build_hooks_github');
  }

  /**
   * Tests Github Pipelines front-end environment deployments.
   *
   * @dataProvider providerFrontendEnvironment
   */
  public function testGithubFrontendEnvironment(string $deployment_strategy = TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE) {
    $random = $this->getRandomGenerator()->name();

    $this->assertFrontendEnvironmentBuildHook(
      'github',
      $deployment_strategy,
      'https://api.github.com/repos/' . $random . '-owner/' . $random . '-repo/actions/workflows/' . $random . '-workflow_id/dispatches',
      [
        'build_hook_url' => 'https://api.github.com/repos/' . $random . '-owner/' . $random . '-repo/actions/workflows/' . $random . '-workflow_id/dispatches',
        'branch' => 'branch',
      ],
      new Response(201, [], 'Hello, Github'));
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
