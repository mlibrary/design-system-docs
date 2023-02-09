<?php

namespace Drupal\build_hooks;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Frontend environment entities.
 */
class FrontendEnvironmentListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Frontend environment');
    $header['plugin'] = $this->t('Type');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\build_hooks\Entity\FrontendEnvironment $entity */
    $row['label'] = $entity->label();
    $row['plugin'] = $entity->getPlugin()->getPluginDefinition()['label'];
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }

}
