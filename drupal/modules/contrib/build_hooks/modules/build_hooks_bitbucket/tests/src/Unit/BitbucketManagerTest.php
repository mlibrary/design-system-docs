<?php

namespace Drupal\Tests\build_hooks_bitbucket\Unit;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\build_hooks_bitbucket\BitbucketManager;
use Drupal\Tests\UnitTestCase;
use GuzzleHttp\ClientInterface;

/**
 * Defines a test for BitBucketManager.
 *
 * @group build_hooks_bitbucket
 */
class BitbucketManagerTest extends UnitTestCase {

  /**
   * Tests getting build hook details..
   */
  public function testGetBuildHookDetailsForPluginConfiguration() {
    $configFactory = $this->getConfigFactoryStub([
      'build_hooks_bitbucket.settings' => [
        'username' => 'fooUser',
        'password' => 'barPassword',
      ],
    ]);
    $httpClient = $this->prophesize(ClientInterface::class);
    $dateFormatter = $this->createMock('\Drupal\Core\Datetime\DateFormatterInterface');
    $manager = new BitbucketManager($configFactory, $httpClient->reveal(), $dateFormatter);
    $random = $this->getRandomGenerator()->name();

    $settings = [
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
    ];
    $result = $manager->getBuildHookDetailsForPluginConfiguration($settings);
    $this->assertInstanceOf(BuildHookDetails::class, $result);

    $this->assertEquals('https://api.bitbucket.org/2.0/repositories/' . $random . '-workspace/' . $random . '-slug/pipelines/', $result->getUrl());
    $this->assertEquals('POST', $result->getMethod());

    $expectedOptions = [
      'json' => [
        'target' => [
          'type' => 'pipeline_ref_target',
          'ref_name' => $random . '-ref',
          'ref_type' => 'branch',
          'selector' => [
            'type' => 'pull-requests',
            'pattern' => $random . '-selector',
          ],
        ],
      ],
      'auth' => ['fooUser', 'barPassword'],
    ];
    $this->assertEquals($expectedOptions, $result->getOptions());
  }

}
