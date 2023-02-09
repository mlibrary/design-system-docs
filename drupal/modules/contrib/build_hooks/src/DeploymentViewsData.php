<?php

namespace Drupal\build_hooks;

use Drupal\Core\Entity\Sql\TableMappingInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\views\EntityViewsData;

/**
 * Defines a views data handler for the deployment entity.
 */
class DeploymentViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  protected function mapFieldDefinition($table, $field_name, FieldDefinitionInterface $field_definition, TableMappingInterface $table_mapping, &$table_data) {
    if ($field_name === 'contents' && !$this->moduleHandler->moduleExists('dynamic_entity_reference')) {
      // When the upgrade path is running, the field type does not yet exist.
      return;
    }
    parent::mapFieldDefinition($table, $field_name, $field_definition, $table_mapping, $table_data);
  }

}
