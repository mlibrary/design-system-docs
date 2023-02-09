<?php

namespace Drupal\build_hooks\Plugin\FrontendEnvironment;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'Generic' frontend environment type.
 *
 * @FrontendEnvironment(
 *  id = "generic",
 *  label = "Generic",
 *  description = "Use this type for any environment that supports build hooks."
 * )
 */
class GenericFrontendEnvironment extends FrontendEnvironmentBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return ['build_hook_url' => ''] + parent::defaultConfiguration();
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {
    // For this type of plugin, we only need the build hook url:
    $form['build_hook_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Build hook url'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['build_hook_url']) ? $this->configuration['build_hook_url'] : '',
      '#description' => $this->t("Build hook url for this environment."),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {
    $this->configuration['build_hook_url'] = $form_state->getValue('build_hook_url');
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    $buildHookDetails = new BuildHookDetails();
    $buildHookDetails->setUrl($this->configuration['build_hook_url']);
    $buildHookDetails->setMethod('POST');
    return $buildHookDetails;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {
    // No additional form elements for the deploy form for this type of plugin.
    return [];
  }

}
