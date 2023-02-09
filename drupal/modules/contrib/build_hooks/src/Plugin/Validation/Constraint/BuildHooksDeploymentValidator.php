<?php

namespace Drupal\build_hooks\Plugin\Validation\Constraint;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Defines a validator for the BuildHooksEnvironment constraint.
 */
class BuildHooksDeploymentValidator extends ConstraintValidator implements ContainerInjectionInterface {

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  private $entityTypeManager;

  /**
   * Creates a new BuildHooksEnvironmentConstraintValidator instance.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function validate($entity, Constraint $constraint) {
    /** @var \Drupal\build_hooks\Entity\DeploymentInterface $entity */
    $deployment_storage = $this->entityTypeManager->getStorage($entity->getEntityTypeId());

    if ($entity->isDeployed()) {
      // Deployed entities don't matter.
      return;
    }

    $query = $deployment_storage->getQuery()
      ->condition('status', 0)
      ->condition('environment', $entity->bundle())
      ->range(0, 1)
      ->accessCheck(FALSE);

    if (!$entity->isNew()) {
      $query->condition('did', $entity->id(), '<>');
    }

    $undeployed = $query->execute();
    if ($undeployed) {
      $existing = $deployment_storage->load(reset($undeployed));
      $this->context->buildViolation($constraint->message, [
        '@label' => $existing->label(),
        ':url' => $existing->toUrl()->toString(),
      ])
        ->atPath('status')
        ->addViolation();
    }
  }

}
