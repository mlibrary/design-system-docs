<?php

namespace Drupal\build_hooks_circleci\Plugin\FrontendEnvironment;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Unicode;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Url;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Defines a circle v2 environment.
 *
 * @FrontendEnvironment(
 *  id = "circleciv2",
 *  label = "Circle CI (V2)",
 *  description = "An Circle CI environment using Version 2 of their API"
 * )
 */
class CircleV2 extends FrontendEnvironmentBase implements ContainerFactoryPluginInterface {

  /**
   * HTTP client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  private $httpClient;

  /**
   * Date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatterInterface
   */
  private $formatter;

  /**
   * Constructs a new CircleV2.
   *
   * @param array $configuration
   *   Configuration.
   * @param string $plugin_id
   *   Plugin ID.
   * @param mixed $plugin_definition
   *   Plugin definition.
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   HTTP client.
   * @param \Drupal\Core\Datetime\DateFormatterInterface $formatter
   *   Date formatter.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, ClientInterface $httpClient, DateFormatterInterface $formatter) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->httpClient = $httpClient;
    $this->formatter = $formatter;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('http_client'),
      $container->get('date.formatter')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + [
      'token' => '',
      'type' => 'branch',
      'project' => '',
      'reference' => '',
      'parameters' => [],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {
    $build = parent::frontEndEnvironmentForm($form, $form_state);
    $build['token'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Token'),
      '#default_value' => $this->configuration['token'],
      '#required' => TRUE,
    ];
    $build['project'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Project'),
      '#default_value' => $this->configuration['project'],
      '#required' => TRUE,
      '#description' => $this->t('Enter your project in the form organisation/repository'),
    ];
    $build['type'] = [
      '#type' => 'radios',
      '#title' => $this->t('Reference type'),
      '#default_value' => $this->configuration['type'],
      '#options' => [
        'branch' => $this->t('Branch'),
        'tag' => $this->t('Tag'),
      ],
    ];
    $build['reference'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Branch / Tag'),
      '#default_value' => $this->configuration['reference'],
      '#required' => TRUE,
    ];
    $build['parameters'] = [
      '#caption' => $this->t('Parameters'),
      '#prefix' => '<div id="parameters-add-more">',
      '#suffix' => '</div>',
      '#type' => 'table',
      '#header' => [
        $this->t('Name'),
        $this->t('Value'),
        $this->t('Remove'),
      ],
    ];
    $parameters = $form_state->getCompleteFormState()->getValue([
      'settings',
      'parameters',
    ], $this->configuration['parameters'] ?: [
      [
        'name' => '',
        'type' => 'string',
        'value' => '',
      ],
    ]);
    foreach ($parameters as $ix => $parameter) {
      $build['parameters'][$ix] = [
        'name' => [
          '#type' => 'textfield',
          '#title_display' => 'invisible',
          '#title' => $this->t('Name<span class="visually-hidden"> of parameter @ix</span>', ['@ix' => $ix + 1]),
          '#default_value' => $parameter['name'],
        ],
        'type' => [
          '#type' => 'select',
          '#title_display' => 'invisible',
          '#title' => $this->t('Type<span class="visually-hidden"> of parameter @ix</span>', ['@ix' => $ix + 1]),
          '#options' => [
            'string' => $this->t('String'),
            'boolean' => $this->t('Boolean'),
            'integer' => $this->t('Integer'),
          ],
          '#default_value' => $parameter['type'],
        ],
        'value' => [
          '#type' => 'textfield',
          '#title_display' => 'invisible',
          '#title' => $this->t('Value<span class="visually-hidden"> of parameter @ix</span>', ['@ix' => $ix + 1]),
          '#default_value' => $parameter['value'],
        ],
        'remove' => [
          '#type' => 'submit',
          '#value' => $this->t('Remove %label', [
            '%label' => $parameter['name'] ?: $this->t('item @ix',
              ['@ix' => $ix + 1]),
          ]),
          '#submit' => [[static::class, 'removeParameterSubmit']],
          '#ajax' => [
            'callback' => [static::class, 'updateForm'],
            'wrapper' => 'parameters-add-more',
            'effect' => 'fade',
            'method' => 'replaceWith',
          ],
        ],
      ];
    }
    $build['add_another'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another parameter'),
      '#submit' => [[static::class, 'addMoreSubmit']],
      '#ajax' => [
        'callback' => [static::class, 'updateForm'],
        'wrapper' => 'parameters-add-more',
        'effect' => 'fade',
        'method' => 'replaceWith',
      ],
    ];
    $build['help'] = [
      '#markup' => $this->t('<p>Parameters to send to CircleCI. To send a value of False for a boolean, enter 0.</p>'),
    ];
    return $build;
  }

  /**
   * Submission handler for the "Add another parameter" button.
   */
  public static function addMoreSubmit(array $form, FormStateInterface $form_state) {
    $parameters = $form_state->getValue(['settings', 'parameters'], []);
    $parameters[] = [
      'name' => '',
      'type' => 'string',
      'value' => '',
    ];
    $form_state->setValue(['settings', 'parameters'], $parameters);
    $form_state->setRebuild();
  }

  /**
   * Submission handler for the "Remove parameter" button.
   */
  public static function removeParameterSubmit(array $form, FormStateInterface $form_state) {
    $button = $form_state->getTriggeringElement();
    $parameters = $form_state->getValue(['settings', 'parameters'], []);
    $parents = $button['#parents'];
    // Remove the button.
    array_pop($parents);
    unset($parameters[end($parents)]);
    $form_state->setValue([
      'settings',
      'parameters',
    ], array_values($parameters));
    $form_state->setRebuild();
  }

  /**
   * Ajax callback for the "Add another parameter" button.
   */
  public function updateForm(array $form, FormStateInterface $form_state) {
    return $form['settings']['parameters'] ?? [];
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {
    parent::frontEndEnvironmentSubmit($form, $form_state);
    $this->configuration['token'] = $form_state->getValue('token');
    $this->configuration['project'] = $form_state->getValue('project');
    $this->configuration['reference'] = $form_state->getValue('reference');
    $this->configuration['type'] = $form_state->getValue('type');
    $this->configuration['parameters'] = array_filter($form_state->getValue('parameters'), function (array $item) {
      return !empty($item['name']);
    });
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    $buildHookDetails = new BuildHookDetails();
    $buildHookDetails->setOptions([
      'json' => [
        $this->configuration['type'] => $this->configuration['reference'],
        'parameters' => array_reduce($this->configuration['parameters'], function (array $carry, array $item) {
          switch ($item['type']) {
            case 'boolean':
              $carry[$item['name']] = (bool) $item['value'];
              return $carry;

            case 'integer':
              $carry[$item['name']] = (int) $item['value'];
              return $carry;

            default:
              $carry[$item['name']] = $item['value'];
          }
          return $carry;
        }, []),
      ],
      'auth' => [
        $this->configuration['token'],
        '',
      ],
    ]);
    $buildHookDetails->setMethod('POST');
    $buildHookDetails->setUrl('https://circleci.com/api/v2/project/gh/' . $this->configuration['project'] . '/pipeline');
    return $buildHookDetails;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {
    try {
      $response = $this->httpClient->request('GET',
        sprintf('https://circleci.com/api/v2/project/gh/%s/pipeline/mine?branch=%s',
          $this->configuration['project'], $this->configuration['reference']), [
            'auth' => [
              $this->configuration['token'],
              '',
            ],
          ]
      );
    }
    catch (GuzzleException $e) {
      return [
        'error' => [
          '#markup' => $this->t('Could not get list of recent builds'),
        ],
      ];
    }
    $body = Json::decode($response->getBody());
    $build['builds'] = [
      '#type' => 'table',
      '#attributes' => ['id' => 'ajax-replace-table'],
      '#header' => [
        $this->t('Created'),
        $this->t('Updated'),
        $this->t('Status'),
        $this->t('Link'),
      ],
      '#empty' => $this->t('No recent builds for reference %reference', [
        '%reference' => $this->configuration['reference'],
      ]),
      '#caption' => $this->t('Last 5 builds'),
    ];
    foreach (array_slice(array_filter($body['items'], function (array $item) {
      return ($item['vcs']['branch'] ?? FALSE) === $this->configuration['reference'];
    }), 0, 5) as $item) {
      try {
        $workflow_response = $this->httpClient->request('GET', 'https://circleci.com/api/v2/pipeline/' . $item['id'] . '/workflow', [
          'auth' => [
            $this->configuration['token'],
            '',
          ],
        ]);
        $workflows = Json::decode($workflow_response->getBody());
      }
      catch (GuzzleException $e) {
        $build['builds']['#rows'][] = [
          $this->formatter->format(\DateTime::createFromFormat(\DateTime::RFC3339_EXTENDED,
            $item['created_at'])->getTimestamp(), 'medium'),
          $this->formatter->format(\DateTime::createFromFormat(\DateTime::RFC3339_EXTENDED,
            $item['updated_at'])->getTimestamp(), 'medium'),
          $this->t('Could not get workflows'),
          '-',
        ];
        continue;
      }
      $worflow = reset($workflows['items']);
      $build['builds']['#rows'][] = [
        $this->formatter->format(\DateTime::createFromFormat(\DateTime::RFC3339_EXTENDED,
          $item['created_at'])->getTimestamp(), 'medium'),
        $this->formatter->format(\DateTime::createFromFormat(\DateTime::RFC3339_EXTENDED,
          $item['updated_at'])->getTimestamp(), 'medium'),
        Unicode::ucfirst($worflow['status']),
        [
          'data' => [
            '#type' => 'link',
            '#url' => Url::fromUri(sprintf('https://app.circleci.com/pipelines/github/%s/%d/workflows/%s',
              $this->configuration['project'], $item['number'], $worflow['id'])),
            '#title' => $this->t('View'),
          ],
        ],
      ];
    }

    $build['refresher'] = [
      '#type' => 'button',
      '#ajax' => [
        'callback' => [self::class, 'refresh'],
        'wrapper' => 'ajax-replace-table',
        'effect' => 'fade',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t('Refreshing status...'),
        ],
      ],
      '#value' => $this->t('Refresh'),
    ];
    return $build;
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
  public static function refresh(array $form, FormStateInterface $form_state) {
    return $form['builds'];
  }

  /**
   * {@inheritdoc}
   */
  public function deploymentWasTriggered(ResponseInterface $response): bool {
    return $response->getStatusCode() < 300 && $response->getStatusCode() >= 200;
  }

}
