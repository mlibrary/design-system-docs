<?php

namespace Drupal\build_hooks_bitbucket\Plugin\FrontendEnvironment;

use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\build_hooks_bitbucket\BitbucketManager;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerTrait;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Provides a 'Bitbucket pipelines' frontend environment type.
 *
 * @FrontendEnvironment(
 *  id = "bitbucket",
 *  label = "Bitbucket Pipelines Build",
 *  description = "An environment built by Bitbucket pipelines."
 * )
 */
class BitbucketFrontendEnvironment extends FrontendEnvironmentBase implements ContainerFactoryPluginInterface {

  use MessengerTrait;

  /**
   * Drupal\build_hooks_bitbucket\BitbucketManager definition.
   *
   * @var \Drupal\build_hooks_bitbucket\BitbucketManager
   */
  protected $bitbucketManager;

  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\build_hooks_bitbucket\BitbucketManager $bitbucketManager
   *   The Bitbucket pipelines Manager.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    BitbucketManager $bitbucketManager
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->bitbucketManager = $bitbucketManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('build_hooks_bitbucket.bitbucket_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    return $this->bitbucketManager->getBuildHookDetailsForPluginConfiguration($this->getConfiguration());
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {
    $form['repo'] = ['#type' => 'fieldset'];
    $form['repo']['workspace'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Repo workspace'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['repo']['workspace']) ? $this->configuration['repo']['workspace'] : '',
      '#required' => TRUE,
    ];

    $form['repo']['slug'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Repo Slug'),
      '#maxlength' => 255,
      '#default_value' => isset($this->configuration['repo']['slug']) ? $this->configuration['repo']['slug'] : '',
      '#description' => $this->t('Found in the URL https://bitbucket.org/{workspace}/{repo_slug}'),
      '#required' => TRUE,
    ];

    $form['ref'] = ['#type' => 'fieldset'];
    $form['ref']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Reference type'),
      '#options' => [
        'branch' => 'Branch',
        'tag' => 'Tag',
      ],
      '#default_value' => isset($this->configuration['ref']['type']) ? $this->configuration['ref']['type'] : '',
      '#description' => $this->t('The type of thing we want bitbucket to build.'),
      '#required' => TRUE,
    ];

    $form['ref']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Reference name'),
      '#default_value' => isset($this->configuration['ref']['name']) ? $this->configuration['ref']['name'] : '',
      '#description' => $this->t('The name of the branch or tag to build.'),
      '#required' => TRUE,
    ];

    $form['selector'] = ['#type' => 'fieldset'];
    $form['selector']['type'] = [
      '#type' => 'select',
      '#title' => $this->t('Pipeline type'),
      '#options' => [
        'custom' => $this->t('Custom'),
        'pull-requests' => $this->t('Pull Request'),
      ],
      '#default_value' => isset($this->configuration['selector_type']) ? $this->configuration['selector_type'] : '',
      '#description' => $this->t('The type of pipeline.'),
      '#required' => TRUE,
    ];

    $form['selector']['name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Pipeline name'),
      '#default_value' => isset($this->configuration['selector']['name']) ? $this->configuration['selector']['name'] : '',
      '#description' => $this->t('The name of the pipeline to trigger for this branch.'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {
    $this->configuration['repo'] = $form_state->getValue('repo');
    $this->configuration['ref'] = $form_state->getValue('ref');
    $this->configuration['selector'] = $form_state->getValue('selector');
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {
    // This plugin adds to the deployment form a fieldset displaying the
    // latest deployments:
    $form = [];

    $form['bitbucketDeployments'] = [
      '#type' => 'details',
      '#title' => $this->t('Recent deployments'),
      '#description' => $this->t('Here you can see the details for the latest deployments for this environment.'),
      '#open' => TRUE,
    ];

    try {
      $form['bitbucketDeployments']['table'] = $this->getLastBitbucketDeploymentsTable($this->getConfiguration());

      $form['bitbucketDeployments']['refresher'] = [
        '#type' => 'button',
        '#ajax' => [
          'callback' => [
            self::class,
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
    catch (\Exception $e) {
      $this->messenger()
        ->addError('Unable to retrieve information about the last deployments for this environment. Check configuration.')
        ->addError($e->getMessage());
    }

    return $form;
  }

  /**
   * Gets info about the latest bitbucket deployments for this environment.
   *
   * @param array $settings
   *   The plugin settings array.
   *
   * @return array
   *   Render array.
   *
   * @throws \GuzzleHttp\Exception\GuzzleException
   *   When fetching fails.
   */
  private function getLastBitbucketDeploymentsTable(array $settings) {
    $latest_builds = $this->bitbucketManager->retrieveLatestBuilds($settings, 15);
    $element = [
      '#type' => 'table',
      '#attributes' => ['id' => 'ajax-replace-table'],
      '#header' => [
        $this->t('Build'),
        $this->t('Status'),
        $this->t('Started at'),
        $this->t('Finished at'),
      ],
    ];

    if (!empty($latest_builds)) {
      foreach ($latest_builds['values'] as $pipeline_id => $pipeline_data) {
        $element[$pipeline_id]['build'] = [
          '#type' => 'link',
          '#url' => Url::fromUri($pipeline_data['repository']['links']['html']['href'] . '/addon/pipelines/home', ['fragment' => '!/results/' . $pipeline_data['build_number']]),
          '#title' => 'Build ' . $pipeline_data['build_number'],
          '#attributes' => ['target' => '_blank'],
        ];

        $pipeline_name = $pipeline_data['state']['name'];
        $pipeline_name .= isset($pipeline_data['state']['result']) ? ' - ' . $pipeline_data['state']['result']['name'] : '';
        $element[$pipeline_id]['name'] = [
          '#plain_text' => $pipeline_name,
        ];

        $element[$pipeline_id]['started_at'] = [
          '#type' => 'item',
          '#markup' => !empty($pipeline_data['created_on']) ? $this->bitbucketManager->formatBitbucketDateTime($pipeline_data['created_on']) : '',
        ];

        $element[$pipeline_id]['completed_on'] = [
          '#type' => 'item',
          '#markup' => !empty($pipeline_data['completed_on']) ? $this->bitbucketManager->formatBitbucketDateTime($pipeline_data['completed_on']) : '',
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
    return $form['bitbucketDeployments']['table'];
  }

  /**
   * {@inheritdoc}
   */
  public function deploymentWasTriggered(ResponseInterface $response): bool {
    return $response->getStatusCode() === 201;
  }

}
