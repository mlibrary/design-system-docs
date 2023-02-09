<?php

namespace Drupal\build_hooks_circleci\Plugin\FrontendEnvironment;

use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\build_hooks_circleci\CircleCiManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'CircleCI' frontend environment type.
 *
 * @FrontendEnvironment(
 *  id = "circleci",
 *  label = "Circle CI (V1)",
 *  description = "An environment connected to Circle CI using V1 of their API"
 * )
 */
class CircleCiFrontendEnvironment extends FrontendEnvironmentBase implements ContainerFactoryPluginInterface {

  use MessengerTrait;

  /**
   * Drupal\build_hooks_circleci\CircleCiManager definition.
   *
   * @var \Drupal\build_hooks_circleci\CircleCiManager
   */
  protected $circleCiManager;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\build_hooks_circleci\CircleCiManager $circleCiManager
   *   The Circle CI Manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    CircleCiManager $circleCiManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->circleCiManager = $circleCiManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('build_hooks_circleci.circleci_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {
    $form['project'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project name'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['project']) ? $this->configuration['project'] : '',
      '#description' => $this->t("Circle CI / Github Project name for this environment. Include the organization name."),
      '#required' => TRUE,
    ];

    $form['branch'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Git branch'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['branch']) ? $this->configuration['branch'] : '',
      '#description' => $this->t("Git branch to deploy to for this environment."),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {
    $this->configuration['project'] = $form_state->getValue('project');
    $this->configuration['branch'] = $form_state->getValue('branch');
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    return $this->circleCiManager->getBuildHookDetailsForPluginConfiguration($this->getConfiguration());
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {

    // This plugin adds to the deployment form a fieldset displaying the
    // latest deployments:
    $form = [];

    $form['latestCircleCiDeployments'] = [
      '#type' => 'details',
      '#title' => $this->t('Recent deployments'),
      '#description' => $this->t('Here you can see the details for the latest deployments for this environment.'),
      '#open' => TRUE,
    ];

    try {
      $form['latestCircleCiDeployments']['table'] = $this->getLastCircleCiDeploymentsTable($this->getConfiguration());

      $form['latestCircleCiDeployments']['refresher'] = [
        '#type' => 'button',
        '#ajax' => [
          'callback' => [
            CircleCiFrontendEnvironment::class,
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
   * Gets info about the latest circle ci deployments for this environment.
   *
   * @param array $settings
   *   The plugin settings array.
   *
   * @return array
   *   Renderable array.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   */
  private function getLastCircleCiDeploymentsTable(array $settings) {
    $circleCiData = $this->circleCiManager->retrieveLatestBuildsFromCircleciForEnvironment($settings, 8);
    $element = [
      '#type' => 'table',
      '#attributes' => ['id' => 'ajax-replace-table'],
      '#header' => [
        $this->t('Status'),
        $this->t('Started at'),
        $this->t('Finished at'),
      ],
    ];
    if (!empty($circleCiData)) {

      foreach ($circleCiData as $circleCiDeployment) {

        // @todo HACK: We do not want to show the "validate" jobs:
        if ($circleCiDeployment['build_parameters']['CIRCLE_JOB'] == 'validate') {
          continue;
        }

        $element[$circleCiDeployment['build_num']]['status'] = [
          '#type' => 'item',
          '#markup' => '<strong>' . $circleCiDeployment['status'] . '</strong>',
        ];

        $started_time = $circleCiDeployment['start_time'] ? $this->circleCiManager->formatCircleCiDateTime($circleCiDeployment['start_time']) : '';

        $element[$circleCiDeployment['build_num']]['started_at'] = [
          '#type' => 'item',
          '#markup' => $started_time,
        ];

        $stopped_time = $circleCiDeployment['stop_time'] ? $this->circleCiManager->formatCircleCiDateTime($circleCiDeployment['stop_time']) : '';

        $element[$circleCiDeployment['build_num']]['finished_at'] = [
          '#type' => 'item',
          '#markup' => $stopped_time,
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
    return $form['latestCircleCiDeployments']['table'];
  }

}
