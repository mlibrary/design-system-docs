<?php

namespace Drupal\build_hooks_netlify\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a config form for netlify deployments.
 */
class BuildHooksNetlifyConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'build_hooks_netlify.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'build_hooks_netlify_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('build_hooks_netlify.settings');

    $form['netlify_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Personal access token'),
      '#description' => $this->t('Insert here your personal access token to access the Netlify API. You can create your token in the "Oauth applications" section of your netlify profile.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('netlify_api_key'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Save the api key to configuration:
    $this->config('build_hooks_netlify.settings')
      ->set('netlify_api_key', $form_state->getValue('netlify_api_key'))
      ->save();
  }

}
