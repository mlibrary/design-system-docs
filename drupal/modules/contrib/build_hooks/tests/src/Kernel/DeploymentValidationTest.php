<?php

namespace Drupal\Tests\build_hooks\Kernel;

use Drupal\build_hooks\Entity\Deployment;
use Drupal\Tests\build_hooks\Traits\EnvironmentTestTrait;

/**
 * Defines a class for testing validation constraints for deployment entities.
 *
 * @group build_hooks
 */
class DeploymentValidationTest extends BuildHooksKernelTestBase {

  use EnvironmentTestTrait;

  /**
   * {@inheritdoc}
   */
  protected function setUp() {
    parent::setUp();
    $this->createTestEnvironment();
  }

  /**
   * Tests validation constraint.
   */
  public function testValidation() {
    \Drupal::entityTypeManager()->getStorage('build_hooks_deployment')->getOrCreateNextDeploymentForEnvironment($this->environment);
    /** @var \Drupal\build_hooks\Entity\Deployment $another */
    $another = Deployment::create([
      'label' => $this->randomMachineName(),
      'environment' => ['target_id' => $this->environment->id()],
      'status' => 0,
    ]);
    /** @var \Drupal\Core\Entity\EntityConstraintViolationList $errors */
    $errors = $another->validate();
    $this->assertCount(1, $errors);
    $this->assertRegExp('/There is already an active deployment for this environment/', (string) $errors[0]->getMessage());
    $this->assertEquals('status', $errors[0]->getPropertyPath());
  }

}
