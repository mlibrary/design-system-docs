<?php

namespace Drupal\build_hooks\Plugin\Validation\Constraint;

use Drupal\Core\Entity\Plugin\Validation\Constraint\CompositeConstraintBase;

/**
 * Defines a constraint plugin to ensure there's only one active deployment.
 *
 * @Constraint(
 *   id = "BuildHooksEnvironment",
 *   label = @Translation("One active deployment.", context = "Validation"),
 * )
 */
class BuildHooksDeployment extends CompositeConstraintBase {

  /**
   * The default violation message.
   *
   * @var string
   */
  public $message = 'There is already an active deployment for this environment - see <a href=":url">@label</a>>';

  /**
   * {@inheritdoc}
   */
  public function coversFields() {
    return ['environment', 'status'];
  }

}
