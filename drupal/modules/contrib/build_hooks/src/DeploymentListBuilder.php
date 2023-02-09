<?php

namespace Drupal\build_hooks;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Defines a list builder for deployments.
 */
class DeploymentListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    return [
      'label' => $this->t('Label'),
      'environment' => $this->t('Environment'),
      'status' => $this->t('Status'),
    ] + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\build_hooks\Entity\DeploymentInterface $entity */
    return [
      'label' => $entity->label(),
      'environment' => $entity->bundle(),
      'status' => $entity->isDeployed() ? $this->t('Deployed') : $this->t('Pending'),
    ] + parent::buildRow($entity);
  }

}
