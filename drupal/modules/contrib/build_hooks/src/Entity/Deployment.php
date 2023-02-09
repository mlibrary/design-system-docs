<?php

namespace Drupal\build_hooks\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\RevisionLogEntityTrait;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines an entity to track a deployment.
 *
 * @ContentEntityType(
 *   id = "build_hooks_deployment",
 *   label = @Translation("Deployment"),
 *   label_collection = @Translation("Deployments"),
 *   label_singular = @Translation("deployment"),
 *   label_plural = @Translation("deployments"),
 *   label_count = @PluralTranslation(
 *     singular = "@count deployment",
 *     plural = "@count deployments",
 *   ),
 *   bundle_label = @Translation("Frontend Environment"),
 *   handlers = {
 *     "storage" = "Drupal\build_hooks\DeploymentStorageHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\build_hooks\DeploymentListBuilder",
 *     "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "views_data" = "Drupal\build_hooks\DeploymentViewsData",
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "build_hooks_deployment",
 *   revision_table = "build_hooks_deployment_revision",
 *   entity_keys = {
 *     "id" = "did",
 *     "revision" = "revision_id",
 *     "bundle" = "environment",
 *     "label" = "label",
 *     "langcode" = "langcode",
 *     "uuid" = "uuid",
 *   },
 *   revision_metadata_keys = {
 *     "revision_user" = "revision_user",
 *     "revision_created" = "revision_created",
 *     "revision_log_message" = "revision_log_message",
 *   },
 *   bundle_entity_type = "frontend_environment",
 *   field_ui_base_route = "entity.frontend_environment.edit_form",
 *   admin_permission = "trigger deployments",
 *   links = {
 *     "canonical" = "/admin/build_hooks/deployments/manage/{build_hooks_deployment}",
 *   },
 *   constraints = {
 *     "BuildHooksEnvironment" = {}
 *   }
 * )
 */
class Deployment extends ContentEntityBase implements DeploymentInterface, EntityChangedInterface, RevisionLogInterface {

  use RevisionLogEntityTrait;
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Label'))
      ->setRequired(TRUE)
      ->setTranslatable(TRUE)
      ->setRevisionable(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(new TranslatableMarkup('Deployed'))
      ->setRevisionable(TRUE)
      ->setDefaultValue(FALSE);

    // Add the revision metadata fields.
    $fields += static::revisionLogBaseFieldDefinitions($entity_type);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time the deployment was created.'))
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['deployed'] = BaseFieldDefinition::create('timestamp')
      ->setLabel(t('Created'))
      ->setDescription(t('The time the deployment was deployed.'))
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the deployment was last edited.'))
      ->setRevisionable(TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayConfigurable('form', TRUE);

    $fields['contents'] = BaseFieldDefinition::create('dynamic_entity_reference')
      ->setLabel((string) new TranslatableMarkup('Deployment contents'))
      ->setDescription((string) new TranslatableMarkup('Content entities updated since the last deployment.'))
      ->setRequired(FALSE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'dynamic_entity_reference_label',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE)
      ->setSettings([
        'exclude_entity_types' => TRUE,
        'entity_type_ids' => ['build_hooks_deployment'],
      ]);
    $fields['deleted'] = BaseFieldDefinition::create('string')
      ->setLabel((string) new TranslatableMarkup('Deleted items'))
      ->setDescription((string) new TranslatableMarkup('Content entities deleted since the last deployment.'))
      ->setRequired(FALSE)
      ->setCardinality(FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'string',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function isDeployed(): bool {
    return (bool) $this->get('status')->value;
  }

}
