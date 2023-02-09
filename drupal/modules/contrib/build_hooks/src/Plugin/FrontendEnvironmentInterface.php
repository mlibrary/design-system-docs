<?php

namespace Drupal\build_hooks\Plugin;

use Drupal\build_hooks\Event\BuildTrigger;
use Drupal\Component\Plugin\PluginInspectionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Component\Plugin\ConfigurableInterface;
use Drupal\Component\Plugin\DependentPluginInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines an interface for Frontend environment plugins.
 */
interface FrontendEnvironmentInterface extends ConfigurableInterface, DependentPluginInterface, PluginFormInterface, PluginInspectionInterface {

  /**
   * Get the info to trigger the hook based on the configuration of the plugin.
   *
   * @return \Drupal\build_hooks\BuildHookDetails
   *   An object containing the details to trigger the hook.
   */
  public function getBuildHookDetails();

  /**
   * Allows the plugin to add elements to the deployment form.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current form state.
   *
   * @return array
   *   A form array to add to the deployment form.
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state);

  /**
   * Determine if the deployment was triggered successfully.
   *
   * @param \Psr\Http\Message\ResponseInterface $response
   *   Response for the trigger request.
   *
   * @return bool
   *   TRUE if the deployment succeeded.
   */
  public function deploymentWasTriggered(ResponseInterface $response) : bool;

  /**
   * React before a build is triggered.
   *
   * @param \Drupal\build_hooks\Event\BuildTrigger $trigger
   *   The build trigger.
   */
  public function preDeploymentTrigger(BuildTrigger $trigger) : void;

}
