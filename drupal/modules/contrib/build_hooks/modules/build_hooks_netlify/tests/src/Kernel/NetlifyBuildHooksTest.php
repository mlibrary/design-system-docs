<?php

namespace Drupal\Tests\build_hooks_netlify\Kernel;

use Drupal\build_hooks\TriggerInterface;
use Drupal\Tests\build_hooks\Kernel\BuildHooksKernelTestBase;

/**
 * Defines a class for testing circle CI build hooks module.
 *
 * @group build_hooks_circleci
 */
class NetlifyBuildHooksTest extends BuildHooksKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'build_hooks_netlify',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installConfig('build_hooks_netlify');
  }

  /**
   * Tests default settings.
   */
  public function testDefaultSettings() {
    $this->assertSame('', $this->config('build_hooks_netlify.settings')->get('netlify_api_key'));
  }

  /**
   * Tests deployment hooks.
   */
  public function testDeploymentHooks() {
    $this->assertFrontendEnvironmentBuildHook('netlify', TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE, 'http://example.com?foo=bar', [
      'build_hook_url' => 'http://example.com?foo=bar',
    ]);
  }

}
