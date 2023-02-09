<?php

namespace Drupal\build_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for route titles.
 */
class FrontendEnvironmentTitleController extends ControllerBase {

  /**
   * Get the title of frontend environment from current route.
   *
   * @return string
   *   The name of the frontend environment.
   */
  public function frontendDeploymentTitle() {
    $path = \Drupal::request()->getpathInfo();
    $arg = explode('/', $path);
    $config = \Drupal::config('build_hooks.frontend_environment.' . $arg[4]);
    return $this->t('@env environment deployment', ['@env' => $config->get('label')]);
  }

}
