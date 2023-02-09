<?php

namespace Drupal\build_hooks;

use Drupal\build_hooks\Entity\DeploymentInterface;
use Drupal\build_hooks\Entity\FrontendEnvironmentInterface;
use Drupal\Core\Entity\Sql\SqlEntityStorageInterface;

/**
 * Defines an interface for deployment storage handler.
 */
interface DeploymentStorageHandlerInterface extends SqlEntityStorageInterface {

  /**
   * Gets the next deployment for an environment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   *
   * @return \Drupal\build_hooks\Entity\DeploymentInterface
   *   Next deployment.
   */
  public function getOrCreateNextDeploymentForEnvironment(FrontendEnvironmentInterface $environment) : DeploymentInterface;

  /**
   * Gets the last deployment for an environment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   *
   * @return \Drupal\build_hooks\Entity\DeploymentInterface|null
   *   Last deployment if it exists.
   */
  public function getLastDeploymentForEnvironment(FrontendEnvironmentInterface $environment) : ?DeploymentInterface;

}
