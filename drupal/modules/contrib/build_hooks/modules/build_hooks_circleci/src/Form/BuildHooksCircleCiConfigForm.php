<?php

namespace Drupal\build_hooks_circleci\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a config form for Circle CI.
 */
class BuildHooksCircleCiConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'build_hooks_circleci.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'build_hooks_circle_ci_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('build_hooks_circleci.settings');

    $form['circleci_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('CircleCi api key'),
      '#description' => $this->t('Insert here the api key for Circle CI'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('circleci_api_key'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Save the api key to configuration:
    $this->config('build_hooks_circleci.settings')
      ->set('circleci_api_key', $form_state->getValue('circleci_api_key'))
      ->save();
  }

}
