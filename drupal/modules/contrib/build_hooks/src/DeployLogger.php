<?php

namespace Drupal\build_hooks;

use Drupal\build_hooks\Entity\DeploymentInterface;
use Drupal\build_hooks\Entity\FrontendEnvironment;
use Drupal\build_hooks\Entity\FrontendEnvironmentInterface;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\dynamic_entity_reference\Plugin\Field\FieldType\DynamicEntityReferenceItem;

/**
 * Defines a class for logging items to be deployed.
 */
class DeployLogger {

  /**
   * No longer in use.
   *
   * @deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. There is no replacement, instead work with the deployment content entity. See https://www.drupal.org/node/3172327
   * @see https://www.drupal.org/node/3172327
   */
  const LOGGER_CHANNEL_NAME = 'build_hooks_logger';

  /**
   * The config.factory service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Time.
   *
   * @var \Drupal\Component\Datetime\TimeInterface
   */
  protected $time;

  /**
   * Constructs a new DeployLogger object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   Config factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity type manager.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   Time.
   */
  public function __construct(ConfigFactoryInterface $configFactory, EntityTypeManagerInterface $entityTypeManager, TimeInterface $time) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->time = $time;
  }

  /**
   * Determines if we should log activity related to the passed entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity.
   *
   * @return bool
   *   True if we should log it, false otherwise.
   */
  public function isEntityTypeLoggable(ContentEntityInterface $entity) {
    $entityType = $entity->getEntityTypeId();
    $selectedEntityTypes = $this->configFactory->get('build_hooks.settings')
      ->get('logging.entity_types');
    return in_array($entityType, array_values($selectedEntityTypes), TRUE);
  }

  /**
   * Logs the creation of an entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity.
   */
  public function logEntityCreated(ContentEntityInterface $entity) {
    /** @var \Drupal\build_hooks\DeploymentStorageHandlerInterface $deployment_storage */
    $deployment_storage = $this->entityTypeManager->getStorage('build_hooks_deployment');
    foreach ($this->entityTypeManager->getStorage('frontend_environment')->loadMultiple() as $environment) {
      $deployment = $deployment_storage->getOrCreateNextDeploymentForEnvironment($environment);
      if (!$this->entityAlreadyExistsInDeployment($deployment, $entity)) {
        $deployment->contents[] = [
          'target_id' => $entity->id(),
          'target_type' => $entity->getEntityTypeId(),
        ];
        $deployment->save();
      }
    }
  }

  /**
   * Logs the updating of an entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity.
   */
  public function logEntityUpdated(ContentEntityInterface $entity) {
    $this->logEntityCreated($entity);
  }

  /**
   * Logs the deleting of an entity.
   *
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   The entity.
   */
  public function logEntityDeleted(ContentEntityInterface $entity) {
    /** @var \Drupal\build_hooks\DeploymentStorageHandlerInterface $deployment_storage */
    $deployment_storage = $this->entityTypeManager->getStorage('build_hooks_deployment');
    foreach ($this->entityTypeManager->getStorage('frontend_environment')->loadMultiple() as $environment) {
      $deployment = $deployment_storage->getOrCreateNextDeploymentForEnvironment($environment);
      $deployment->deleted[] = sprintf('%s (%s)', $entity->label(), $this->entityTypeManager->getDefinition($entity->getEntityTypeId())->getLabel());
      $deployment->save();
    }
  }

  /**
   * Get the last deployed time for an environment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   The frontend environment config entity.
   * @param int|null $timestamp
   *   Timestamp.
   */
  public function setLastDeployTimeForEnvironment(FrontendEnvironmentInterface $environment, int $timestamp = NULL) {
    /** @var \Drupal\build_hooks\DeploymentStorageHandlerInterface $deployment_storage */
    $deployment_storage = $this->entityTypeManager->getStorage('build_hooks_deployment');
    $deployment = $deployment_storage->getOrCreateNextDeploymentForEnvironment($environment);
    $deployment->deployed = $timestamp ?: $this->time->getCurrentTime();
    $deployment->status = 1;
    $deployment->save();
  }

  /**
   * Get the last deployed time for an environment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironment $environment
   *   The frontend environment config entity.
   *
   * @return int
   *   The timestamp of the latest deployment for the environment.
   */
  public function getLastDeployTimeForEnvironment(FrontendEnvironment $environment) {
    /** @var \Drupal\build_hooks\DeploymentStorageHandlerInterface $deployment_storage */
    $deployment_storage = $this->entityTypeManager->getStorage('build_hooks_deployment');
    if ($last = $deployment_storage->getLastDeploymentForEnvironment($environment)) {
      return $last->deployed->value;
    }
    return 0;
  }

  /**
   * Gets a list of the last relevant log items after a certain timestamp.
   *
   * @param int $timestamp
   *   The timestamp after which to get the elements.
   *
   * @return array
   *   An array of log items.
   */
  public function getLogItemsSinceTimestamp($timestamp) {
    @trigger_error(__METHOD__ . ' is deprecated in build_hooks:8.x-2.4 and is removed from build_hooks:8.x-3.0. There is no replacement, instead work with the deployment content entity. See https://www.drupal.org/node/3172327', E_USER_DEPRECATED);
    return [];
  }

  /**
   * Gets how many changes have happened since the last deployment for an env.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironment $environment
   *   The frontend environment config entity.
   *
   * @return int
   *   The amount of changes for the environment since last deployment.
   */
  public function getNumberOfItemsSinceLastDeploymentForEnvironment(FrontendEnvironment $environment) {
    /** @var \Drupal\build_hooks\DeploymentStorageHandlerInterface $deployment_storage */
    $deployment_storage = $this->entityTypeManager->getStorage('build_hooks_deployment');
    $next = $deployment_storage->getOrCreateNextDeploymentForEnvironment($environment);
    return count(array_filter(iterator_to_array($next->get('contents')), function (DynamicEntityReferenceItem $item) {
      // Filter out since deleted items.
      return $item->entity;
    })) + $next->get('deleted')->count();
  }

  /**
   * Checks if an entity is already part of a deployment.
   *
   * @param \Drupal\build_hooks\Entity\DeploymentInterface $deployment
   *   Deployment.
   * @param \Drupal\Core\Entity\ContentEntityInterface $entity
   *   Entity.
   *
   * @return bool
   *   TRUE if the entity already exists in the deployment.
   */
  protected function entityAlreadyExistsInDeployment(DeploymentInterface $deployment, ContentEntityInterface $entity) : bool {
    if ($deployment->get('contents')->isEmpty()) {
      return FALSE;
    }
    $existing_items = array_column(array_filter($deployment->get('contents')->getValue(), function (array $item) use ($entity) {
      return $item['target_type'] === $entity->getEntityTypeId();
    }), 'target_id');
    $string_ids = array_map(function ($id) {
      return (string) $id;
    }, $existing_items);
    // Item IDs can be strings or integers, so we convert them all to string
    // so we can make a type-safe in-array comparison.
    if (in_array((string) $entity->id(), $string_ids, TRUE)) {
      // This item is already logged.
      return TRUE;
    }
    return FALSE;
  }

}
