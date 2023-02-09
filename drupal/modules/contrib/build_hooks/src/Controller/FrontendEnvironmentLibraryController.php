<?php

namespace Drupal\build_hooks\Controller;

use Drupal\build_hooks\Plugin\FrontendEnvironmentManager;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a list of frontend environment plugins.
 */
class FrontendEnvironmentLibraryController extends ControllerBase {

  /**
   * The frontend environment manager.
   *
   * @var \Drupal\build_hooks\Plugin\FrontendEnvironmentManager
   */
  protected $frontendEnvironmentManager;

  /**
   * FrontendEnvironmentLibraryController constructor.
   *
   * @param \Drupal\build_hooks\Plugin\FrontendEnvironmentManager $frontendEnvironmentManager
   *   The frontend environment manager.
   */
  public function __construct(FrontendEnvironmentManager $frontendEnvironmentManager) {
    $this->frontendEnvironmentManager = $frontendEnvironmentManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.frontend_environment')
    );
  }

  /**
   * Shows a list of frontend environments that can be added.
   *
   * @return array
   *   A render array as expected by the renderer.
   */
  public function listFrontendEnvironments() {

    $headers = [
      ['data' => $this->t('Type')],
      ['data' => $this->t('Description')],
      ['data' => $this->t('Operations')],
    ];

    $definitions = $this->frontendEnvironmentManager->getDefinitions();

    $rows = [];
    foreach ($definitions as $plugin_id => $plugin_definition) {
      $row = [];
      $row['title'] = $plugin_definition['label'];
      $row['description']['data'] = $plugin_definition['description'];

      $links['add'] = [
        'title' => $this->t('Add new environment'),
        'url' => Url::fromRoute('build_hooks.admin_add', ['plugin_id' => $plugin_id]),
      ];

      $row['operations']['data'] = [
        '#type' => 'operations',
        '#links' => $links,
      ];
      $rows[] = $row;
    }

    $build['frontend_environments'] = [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No types available. Please enable one of the submodules or add your own custom plugin.'),
    ];

    return $build;
  }

}
