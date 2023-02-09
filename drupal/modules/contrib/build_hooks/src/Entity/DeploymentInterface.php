<?php

namespace Drupal\build_hooks\Entity;

use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines an interface for a deployment entity.
 */
interface DeploymentInterface extends ContentEntityInterface {

  /**
   * Gets the deployment status.
   *
   * @return bool
   *   Deployment status.
   */
  public function isDeployed() : bool;

}
