<?php

namespace Drupal\Tests\build_hooks\Functional;

use Drupal\Core\Entity\EntityDefinitionUpdateManagerInterface;
use Drupal\dynamic_entity_reference\Plugin\Field\FieldType\DynamicEntityReferenceItem;
use Drupal\FunctionalTests\Update\UpdatePathTestBase;

/**
 * Defines a class for testing the update to the deployment entity.
 *
 * @group build_hooks
 */
class DeploymentUpdateTest extends UpdatePathTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected function setDatabaseDumpFiles() {
    $this->databaseDumpFiles = [
      __DIR__ . '/../../fixtures/update/pre-deployment-entity-d89-minimal.php.gz',
      __DIR__ . '/../../fixtures/update/pre-deployment-entity-d90-minimal.php.gz',
    ];
  }

  /**
   * Tests the deployment update path.
   */
  public function testUpdatePath() {
    $this->assertEquals(EntityDefinitionUpdateManagerInterface::DEFINITION_CREATED, \Drupal::service('entity.definition_update_manager')->getChangeList()['build_hooks_deployment']['entity_type']);
    $this->assertFalse(\Drupal::moduleHandler()->moduleExists('dynamic_entity_reference'));
    $this->runUpdates();
    $this->assertTrue(\Drupal::moduleHandler()->moduleExists('dynamic_entity_reference'));
    $this->assertTrue(empty(\Drupal::service('entity.definition_update_manager')->getChangeList()['build_hooks_deployment']));
    $created = \Drupal::entityTypeManager()->getStorage('build_hooks_deployment')->loadMultiple();
    $this->assertCount(2, $created);
    $test1 = \Drupal::entityTypeManager()->getStorage('build_hooks_deployment')->loadByProperties(['environment' => 'test']);
    $this->assertNotEmpty($test1);
    $test1 = reset($test1);
    $this->assertEquals([
      'Test 1',
      'Test 2',
      'Test 3',
    ], array_values(array_map(function (DynamicEntityReferenceItem $item) {
      return $item->entity->label();
    }, iterator_to_array($test1->get('contents')))));
    $test2 = \Drupal::entityTypeManager()->getStorage('build_hooks_deployment')->loadByProperties(['environment' => 'test2']);
    $this->assertNotEmpty($test2);
    $test2 = reset($test2);
    $this->assertEquals([
      'Test 1',
      'Test 2',
      'Test 3',
    ], array_values(array_map(function (DynamicEntityReferenceItem $item) {
      return $item->entity->label();
    }, iterator_to_array($test2->get('contents')))));
    $this->assertFalse($test1->isDeployed());
    $this->assertFalse($test2->isDeployed());
    $this->assertEquals('Delete 1 (Test entity)', $test1->deleted->value);
    $this->assertEquals('Delete 1 (Test entity)', $test2->deleted->value);
  }

}
