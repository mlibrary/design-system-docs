<?php

namespace Drupal\build_hooks_github\Plugin\FrontendEnvironment;

use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\build_hooks\BuildHookDetails;

/**
 * Provides a 'Github' frontend environment type.
 *
 * @FrontendEnvironment(
 *  id = "github",
 *  label = "Github",
 *  description = "A Github API endpoint"
 * )
 */
class GithubFrontendEnvironment extends FrontendEnvironmentBase implements ContainerFactoryPluginInterface {

  /**
   * The github config.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config_factory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config_factory->get('build_hooks_github.settings');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {

    $form['build_hook_url'] = [
      '#type' => 'url',
      '#title' => $this->t('Build hook url'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['build_hook_url']) ? $this->configuration['build_hook_url'] : '',
      '#description' => $this->t("Build hook url for this environment and branch."),
      '#required' => TRUE,
    ];

    $form['branch'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Git branch'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['branch']) ? $this->configuration['branch'] : '',
      '#description' => $this->t("Git branch that the build hook refers to."),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {
    $this->configuration['branch'] = $form_state->getValue('branch');
    $this->configuration['build_hook_url'] = $form_state->getValue('build_hook_url');
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    $buildHookDetails = new BuildHookDetails();
    $buildHookDetails->setUrl($this->configuration['build_hook_url']);
    $buildHookDetails->setMethod('POST');
    $access_token = $this->config->get('github_access_token');

    $buildHookDetails->setOptions(
      [
        'headers' =>
          [
            'Content-Type' => 'application/json',
            'Authorization' => 'token ' . $access_token,
          ],
        'body' => '{"ref":"' . $this->configuration['branch'] . '"}',
      ],
    );
    return $buildHookDetails;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {
    $form = [];
    return $form;
  }

}
