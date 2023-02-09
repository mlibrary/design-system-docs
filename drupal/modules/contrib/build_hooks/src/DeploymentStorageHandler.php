<?php

namespace Drupal\build_hooks;

use Drupal\build_hooks\Entity\DeploymentInterface;
use Drupal\build_hooks\Entity\FrontendEnvironmentInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a storage handler for Deployment entities.
 */
class DeploymentStorageHandler extends SqlContentEntityStorage implements DeploymentStorageHandlerInterface {

  /**
   * Date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  protected $dateFormatter;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    /** @var self $instance */
    $instance = parent::createInstance($container, $entity_type);
    $instance->dateFormatter = $container->get('date.formatter');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getOrCreateNextDeploymentForEnvironment(FrontendEnvironmentInterface $environment): DeploymentInterface {
    $ids = $this->getQuery()
      ->condition('status', 0)
      ->condition('environment', $environment->id())
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($ids) {
      return $this->load(reset($ids));
    }

    $environment = $this->create([
      'environment' => $environment->id(),
      'status' => 0,
      'label' => $this->getLabelForNextDeploymentForEnvironment($environment),
    ]);
    $environment->save();
    return $environment;
  }

  /**
   * {@inheritdoc}
   */
  public function getLastDeploymentForEnvironment(FrontendEnvironmentInterface $environment): ?DeploymentInterface {
    $ids = $this->getQuery()
      ->condition('status', 1)
      ->condition('environment', $environment->id())
      ->sort('deployed', 'DESC')
      ->range(0, 1)
      ->accessCheck(FALSE)
      ->execute();
    if ($ids) {
      return $this->load(reset($ids));
    }
    return NULL;
  }

  /**
   * Gets the label for the next deployment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   *
   * @return string
   *   Label.
   */
  protected function getLabelForNextDeploymentForEnvironment(FrontendEnvironmentInterface $environment) : string {
    if ($last = $this->getLastDeploymentForEnvironment($environment)) {
      return sprintf('Items changed since %s for %s', $this->dateFormatter->format($last->deployed->value), $environment->label());
    }
    return sprintf('First deployment for %s', $environment->label());
  }

}
