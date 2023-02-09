<?php

namespace Drupal\build_hooks\Plugin;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;

/**
 * Provides the Frontend environment plugin manager.
 */
class FrontendEnvironmentManager extends DefaultPluginManager {

  /**
   * Constructs a new FrontendEnvironmentManager object.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/FrontendEnvironment', $namespaces, $module_handler, 'Drupal\build_hooks\Plugin\FrontendEnvironmentInterface', 'Drupal\build_hooks\Annotation\FrontendEnvironment');

    $this->alterInfo('build_hooks_frontend_environment_info');
    $this->setCacheBackend($cache_backend, 'build_hooks_frontend_environment_plugins');
  }

}
