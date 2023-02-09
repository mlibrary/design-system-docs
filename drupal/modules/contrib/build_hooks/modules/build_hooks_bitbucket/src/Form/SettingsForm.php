<?php

namespace Drupal\build_hooks_bitbucket\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure build_hooks_bitbucket settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Bitbucket manager.
   *
   * @var \Drupal\build_hooks_bitbucket\BitbucketManager
   */
  protected $manager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $static = parent::create($container);
    $static->manager = $container->get('build_hooks_bitbucket.bitbucket_manager');
    return $static;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'build_hooks_bitbucket_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['build_hooks_bitbucket.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('build_hooks_bitbucket.settings');
    $form['username'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Username'),
      '#default_value' => $config->get('username'),
    ];

    $form['password'] = [
      '#type' => 'password',
      '#title' => $this->t('App Password'),
    ];
    if (!empty($config->get('password'))) {
      $form['password']['#description'] = $this->t('Password set. Leave blank to keep current password.');
    }
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('build_hooks_bitbucket.settings');
    $config->set('username', $form_state->getValue('username'));
    if (!empty($form_state->getValue('password'))) {
      $config->set('password', $form_state->getValue('password'));
    }
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
