<?php

namespace Drupal\build_hooks;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Plugin\DefaultSingleLazyPluginCollection;

/**
 * Provides a collection of front plugins.
 */
class FrontendEnvironmentPluginCollection extends DefaultSingleLazyPluginCollection {

  /**
   * The frontend environment ID this plugin collection belongs to.
   *
   * @var string
   */
  protected $frontendEnvironmentId;

  /**
   * Constructs a new BlockPluginCollection.
   *
   * @param \Drupal\Component\Plugin\PluginManagerInterface $manager
   *   The manager to be used for instantiating plugins.
   * @param string $instance_id
   *   The ID of the plugin instance.
   * @param array $configuration
   *   An array of configuration.
   * @param string $frontend_environment_id
   *   The unique ID of the frontend environment entity using this plugin.
   */
  public function __construct(PluginManagerInterface $manager, $instance_id, array $configuration, $frontend_environment_id) {
    parent::__construct($manager, $instance_id, $configuration);

    $this->frontendEnvironmentId = $frontend_environment_id;
  }

  /**
   * {@inheritdoc}
   */
  protected function initializePlugin($instance_id) {
    if (!$instance_id) {
      throw new PluginException("The frontend environment '{$this->frontendEnvironmentId}' did not specify a plugin.");
    }

    try {
      parent::initializePlugin($instance_id);
    }
    catch (PluginException $e) {
      $module = $this->configuration['provider'];
      // Ignore blocks belonging to uninstalled modules, but re-throw valid
      // exceptions when the module is installed and the plugin is
      // misconfigured.
      if (!$module || \Drupal::moduleHandler()->moduleExists($module)) {
        throw $e;
      }
    }
  }

}
