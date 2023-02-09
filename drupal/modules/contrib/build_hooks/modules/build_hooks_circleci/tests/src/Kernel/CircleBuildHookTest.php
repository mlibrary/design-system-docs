<?php

namespace Drupal\Tests\build_hooks_circleci\Kernel;

use Drupal\build_hooks\TriggerInterface;
use Drupal\build_hooks\Entity\FrontendEnvironment;
use Drupal\build_hooks\Trigger;
use Drupal\Core\Form\FormState;
use Drupal\Tests\build_hooks\Kernel\BuildHooksKernelTestBase;
use GuzzleHttp\Psr7\Response;

/**
 * Defines a class for testing circle CI build hooks module.
 *
 * @group build_hooks_circleci
 */
class CircleBuildHookTest extends BuildHooksKernelTestBase {

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'build_hooks_circleci',
  ];

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->installConfig('system');
    $this->installConfig('build_hooks_circleci');
  }

  /**
   * Tests default settings.
   */
  public function testDefaultSettings() {
    $this->assertSame('', $this->config('build_hooks_circleci.settings')->get('circleci_api_key'));
  }

  /**
   * Tests deployment hooks.
   */
  public function testDeploymentHooks() {
    $api_key = $this->randomMachineName();
    $this->config('build_hooks_circleci.settings')->set('circleci_api_key', $api_key)->save();
    $project = $this->randomMachineName();
    $branch = $this->randomMachineName();
    $expected_url = 'https://circleci.com/api/v1.1/project/github/' . $project . '/build?circle-token=' . $api_key;
    $request = $this->assertFrontendEnvironmentBuildHook('circleci', TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE, $expected_url, [
      'project' => $project,
      'branch' => $branch,
    ]);
    $this->assertEquals(json_encode([
      'branch' => $branch,
    ]), (string) $request->getBody());
  }

  /**
   * Tests deployment hooks for version 2.
   */
  public function testDeploymentHooksV2() {
    $project = $this->randomMachineName();
    $branch = $this->randomMachineName();
    $parameter_name = $this->randomMachineName();
    $parameter_value = $this->randomMachineName();
    $api_key = $this->randomMachineName();
    $expected_url = 'https://circleci.com/api/v2/project/gh/' . $project . '/pipeline';
    $request = $this->assertFrontendEnvironmentBuildHook('circleciv2', Trigger::DEPLOYMENT_STRATEGY_ENTITYSAVE, $expected_url, [
      'project' => $project,
      'type' => 'branch',
      'reference' => $branch,
      'parameters' => [
        [
          'name' => $parameter_name,
          'value' => $parameter_value,
          'type' => 'string',
        ],
        ['name' => 'param2', 'value' => '2', 'type' => 'integer'],
        ['name' => 'param3', 'value' => '0', 'type' => 'boolean'],
        ['name' => 'param4', 'value' => 'true', 'type' => 'boolean'],
      ],
      'token' => $api_key,
    ]);
    $this->assertEquals(json_encode([
      'branch' => $branch,
      'parameters' => [
        $parameter_name => $parameter_value,
        'param2' => 2,
        'param3' => FALSE,
        'param4' => TRUE,
      ],
    ]), (string) $request->getBody());
    $this->assertEquals(['application/json'], $request->getHeader('Content-Type'));
    $this->assertEquals(['Basic ' . base64_encode($api_key . ':')], $request->getHeader('Authorization'));
    $this->assertEquals('POST', $request->getMethod());
  }

  /**
   * Tests deployment info.
   */
  public function testDeploymentInfo() {
    $directory = dirname(__FILE__, 3) . '/fixtures';
    $this->mockClient(
      new Response('200', [], file_get_contents($directory . '/builds.json')),
      new Response('200', [], file_get_contents($directory . '/workflow-1.json')),
      new Response('200', [], file_get_contents($directory . '/workflow-2.json')),
      new Response('200', [], file_get_contents($directory . '/workflow-3.json')),
      new Response('200', [], file_get_contents($directory . '/workflow-4.json')),
      new Response('200', [], file_get_contents($directory . '/workflow-5.json')),
      // Passing jobs request, failing workflows request.
      new Response('200', [], file_get_contents($directory . '/builds.json')),
      new Response('500', [], json_encode([])),
      new Response('500', [], json_encode([])),
      new Response('500', [], json_encode([])),
      new Response('500', [], json_encode([])),
      new Response('500', [], json_encode([])),
      // Failing jobs request.
      new Response('500', [], json_encode([]))
    );
    $title = $this->randomMachineName();
    /** @var \Drupal\build_hooks\Entity\FrontendEnvironment $environment */
    $project = 'foo/bar';
    $environment = FrontendEnvironment::create([
      'id' => 'foo',
      'label' => $title,
      'settings' => [
        'provider' => 'build_hooks_circleci',
        'project' => $project,
        'type' => 'branch',
        'reference' => 'master',
        'parameters' => [],
        'token' => '12345678910',
      ],
      'plugin' => 'circleciv2',
      'deployment_strategy' => Trigger::DEPLOYMENT_STRATEGY_CRON,
    ]);
    $environment->save();
    /** @var \Drupal\build_hooks\Plugin\FrontendEnvironmentInterface $plugin */
    $plugin = $environment->getPlugin();
    $extra = $plugin->getAdditionalDeployFormElements(new FormState())['builds'];
    $expected_url = 'https://circleci.com/api/v2/project/gh/' . $project . '/pipeline/mine?branch=master';
    $request = reset($this->history)['request'];
    $this->assertEquals($expected_url, (string) $request->getUri());
    $this->assertEquals('table', $extra['#type']);
    $this->assertCount(5, $extra['#rows']);
    $row = reset($extra['#rows']);
    $this->assertEquals('https://app.circleci.com/pipelines/github/foo/bar/57/workflows/1be799d9-4607-4a48-8bc4-27b9dbcb0958', $row[3]['data']['#url']->toString());
    $this->assertEquals('Success', $row[2]);
    $this->assertEquals('Canceled', end($extra['#rows'])[2]);

    // Now with error handling - workflows fail.
    $extra = $plugin->getAdditionalDeployFormElements(new FormState())['builds'];
    foreach ($extra['#rows'] as $row) {
      $this->assertEquals('Could not get workflows', $row[2]);
    }

    // Now with error handling - jobs fail.
    $extra = $plugin->getAdditionalDeployFormElements(new FormState());
    $this->assertEquals('Could not get list of recent builds', (string) $extra['error']['#markup']);
  }

}
