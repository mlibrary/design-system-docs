<?php

namespace Drupal\Tests\build_hooks_bitbucket\Kernel;

use Drupal\build_hooks\TriggerInterface;
use Drupal\Tests\build_hooks\Kernel\BuildHooksKernelTestBase;
use GuzzleHttp\Psr7\Response;

/**
 * Defines a test for the configuration form of the bitbucket pipelines plugin.
 *
 * @group build_hooks_bitbucket
 */
class BitbucketBuildHooksTest extends BuildHooksKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'build_hooks',
    'build_hooks_bitbucket',
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
    $this->installConfig('build_hooks_bitbucket');
  }

  /**
   * Tests Bitbucket Pipelines front-end environment deployments.
   *
   * @dataProvider providerFrontendEnvironment
   */
  public function testBitbucketFrontendEnvironment(string $deployment_strategy = TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE) {
    $random = $this->getRandomGenerator()->name();

    $this->assertFrontendEnvironmentBuildHook(
      'bitbucket',
      $deployment_strategy,
      'https://api.bitbucket.org/2.0/repositories/' . $random . '-workspace/' . $random . '-slug/pipelines/',
      [
        'repo' => [
          'workspace' => $random . '-workspace',
          'slug' => $random . '-slug',
        ],
        'ref' => [
          'name' => $random . '-ref',
          'type' => 'branch',
        ],
        'selector' => [
          'name' => $random . '-selector',
          'type' => 'pull-requests',
        ],
      ],
      new Response(201, [], 'Hello, Bitbucket'));
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
