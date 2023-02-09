<?php

namespace Drupal\Tests\build_hooks\Kernel;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\Tests\build_hooks\Traits\EnvironmentTestTrait;
use GuzzleHttp\Psr7\Response;

/**
 * Defines a class for testing deployment storage handler.
 *
 * @group build_hooks
 */
class DeploymentStorageHandlerTest extends BuildHooksKernelTestBase {

  use EnvironmentTestTrait;

  const MOCK_TIME = 1600745446;

  /**
   * Deployment storage.
   *
   * @var \Drupal\build_hooks\DeploymentStorageHandlerInterface
   */
  protected $storage;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->createTestEnvironment();
    $time = $this->prophesize(TimeInterface::class);
    $time->getCurrentTime()->willReturn(self::MOCK_TIME);
    $this->container->set('datetime.time', $time->reveal());
    $this->storage = \Drupal::entityTypeManager()->getStorage('build_hooks_deployment');
    $this->mockClient(new Response(200, [], 'It worked!'));
  }

  /**
   * Tests deployment storage handler.
   */
  public function testDeploymentStorageHandler() {
    $next = $this->storage->getOrCreateNextDeploymentForEnvironment($this->environment);
    $this->assertEquals(sprintf('First deployment for %s', $this->environment->label()), $next->label());
    $this->assertFalse($next->isDeployed());
    $this->assertTrue($next->get('deployed')->isEmpty());
    $this->assertTrue($next->get('contents')->isEmpty());
    $this->assertTrue($next->get('deleted')->isEmpty());

    $entity = EntityTest::create([
      'name' => $this->randomMachineName(),
    ]);
    $entity->save();

    $new_next = $this->storage->getOrCreateNextDeploymentForEnvironment($this->environment);
    $this->assertNotEquals($next->id(), $new_next->id());
    $last = $this->storage->getLastDeploymentForEnvironment($this->environment);
    $this->assertEquals($next->id(), $last->id());
    $this->assertTrue($last->isDeployed());
    $this->assertEquals(self::MOCK_TIME, $last->deployed->value);
    $this->assertEquals($entity->id(), $last->contents->entity->id());
    $this->assertEquals($entity->getEntityTypeId(), $last->contents->entity->getEntityTypeId());
    $this->assertEquals(sprintf('Items changed since %s for %s', \Drupal::service('date.formatter')->format(self::MOCK_TIME), $this->environment->label()), $new_next->label());
  }

}
