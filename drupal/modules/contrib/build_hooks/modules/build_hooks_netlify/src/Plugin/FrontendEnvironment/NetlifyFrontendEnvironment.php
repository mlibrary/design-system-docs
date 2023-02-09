<?php

namespace Drupal\build_hooks_netlify\Plugin\FrontendEnvironment;

use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\build_hooks_netlify\NetlifyManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\build_hooks\BuildHookDetails;

/**
 * Provides a 'Netlify' frontend environment type.
 *
 * @FrontendEnvironment(
 *  id = "netlify",
 *  label = "Netlify",
 *  description = "An environment on Netlify"
 * )
 */
class NetlifyFrontendEnvironment extends FrontendEnvironmentBase implements ContainerFactoryPluginInterface {

  use MessengerTrait;

  /**
   * Drupal\build_hooks_netlify\NetlifyManager definition.
   *
   * @var \Drupal\build_hooks_netlify\NetlifyManager
   */
  protected $netlifyManager;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\build_hooks_netlify\NetlifyManager $netlifyManager
   *   The Netlify Manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    NetlifyManager $netlifyManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->netlifyManager = $netlifyManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('build_hooks_netlify.netlify_manager')
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

    $form['api_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('API id'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['api_id']) ? $this->configuration['api_id'] : '',
      '#description' => $this->t("Netlify API ID of this environment"),
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
    $this->configuration['api_id'] = $form_state->getValue('api_id');
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
    return $buildHookDetails;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {

    // This plugin adds to the deployment form a fieldset displaying the
    // latest deployments:
    $form = [];

    $form['latestNetlifyDeployments'] = [
      '#type' => 'details',
      '#title' => $this->t('Recent deployments'),
      '#description' => $this->t('Here you can see the details for the latest deployments for this environment.'),
      '#open' => TRUE,
    ];

    try {
      $form['latestNetlifyDeployments']['table'] = $this->getLastNetlifyDeploymentsTable($this->getConfiguration());

      $form['latestNetlifyDeployments']['refresher'] = [
        '#type' => 'button',
        '#ajax' => [
          'callback' => [
            NetlifyFrontendEnvironment::class,
            'refreshDeploymentTable',
          ],
          'wrapper' => 'ajax-replace-table',
          'effect' => 'fade',
          'progress' => [
            'type' => 'throbber',
            'message' => $this->t('Refreshing deployment status...'),
          ],
        ],
        '#value' => $this->t('Refresh'),
      ];
    }
    catch (GuzzleException $e) {
      $this->messenger()->addError('Unable to retrieve information about the last deployments for this environment. Check configuration.');
    }

    return $form;
  }

  /**
   * Displays info about the latest netlify deployments for this environment.
   *
   * @param array $settings
   *   The plugin settings array.
   *
   * @return array
   *   Renderable array.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function getLastNetlifyDeploymentsTable(array $settings) {
    $netlifyData = $this->netlifyManager->retrieveLatestBuildsFromNetlifyForEnvironment($settings, 8);
    $element = [
      '#type' => 'table',
      '#attributes' => ['id' => 'ajax-replace-table'],
      '#header' => [
        $this->t('Status'),
        $this->t('Started at'),
        $this->t('Finished at'),
        $this->t('Message'),
      ],
    ];
    if (!empty($netlifyData)) {

      foreach ($netlifyData as $netlifyDeployment) {

        $element[$netlifyDeployment['id']]['status'] = [
          '#type' => 'item',
          '#markup' => '<strong>' . $netlifyDeployment['state'] . '</strong>',
        ];

        $started_time = $netlifyDeployment['created_at'] ? $this->netlifyManager->formatNetlifyDateTime($netlifyDeployment['created_at']) : '';

        $element[$netlifyDeployment['id']]['started_at'] = [
          '#type' => 'item',
          '#markup' => $started_time,
        ];

        $stopped_time = $netlifyDeployment['published_at'] ? $this->netlifyManager->formatNetlifyDateTime($netlifyDeployment['published_at']) : '';

        $element[$netlifyDeployment['id']]['finished_at'] = [
          '#type' => 'item',
          '#markup' => $stopped_time,
        ];

        $message = $netlifyDeployment['error_message'] ? $netlifyDeployment['error_message'] : '';

        $element[$netlifyDeployment['id']]['error_message'] = [
          '#type' => 'item',
          '#markup' => $message,
        ];
      }
    }
    return $element;
  }

  /**
   * Ajax form callback to rebuild the latest deployments table.
   *
   * @param array $form
   *   The form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state of the form.
   *
   * @return array
   *   The form array to add back to the form.
   */
  public static function refreshDeploymentTable(array $form, FormStateInterface $form_state) {
    return $form['latestNetlifyDeployments']['table'];
  }

}
