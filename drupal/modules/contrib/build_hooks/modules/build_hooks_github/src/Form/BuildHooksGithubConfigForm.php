<?php

namespace Drupal\build_hooks_github\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a config form for Github deployments.
 */
class BuildHooksGithubConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'build_hooks_github.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'build_hooks_github_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('build_hooks_github.settings');

    $form['github_access_token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Personal access token'),
      '#description' => $this->t('Insert here your personal access token to access the Github API. You can create your token in the "Developer Settings" section of your account.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('github_access_token'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    // Save the api key to configuration:
    $this->config('build_hooks_github.settings')
      ->set('github_access_token', $form_state->getValue('github_access_token'))
      ->save();
  }

}
