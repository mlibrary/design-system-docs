<?php

namespace Drupal\build_hooks\Entity;

use Drupal\build_hooks\FrontendEnvironmentPluginCollection;
use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\Core\Entity\EntityWithPluginCollectionInterface;

/**
 * Defines the Frontend environment entity.
 *
 * @ConfigEntityType(
 *   id = "frontend_environment",
 *   label = @Translation("Front end environment"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\build_hooks\FrontendEnvironmentListBuilder",
 *     "form" = {
 *       "default" = "Drupal\build_hooks\Form\FrontendEnvironmentForm",
 *       "edit" = "Drupal\build_hooks\Form\FrontendEnvironmentForm",
 *       "delete" = "Drupal\build_hooks\Form\FrontendEnvironmentDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     },
 *   },
 *   bundle_of = "build_hooks_deployment",
 *   config_prefix = "frontend_environment",
 *   admin_permission = "manage frontend environments",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/config/build_hooks/frontend_environment/{frontend_environment}",
 *     "edit-form" = "/admin/config/build_hooks/frontend_environment/{frontend_environment}/edit",
 *     "delete-form" = "/admin/config/build_hooks/frontend_environment/{frontend_environment}/delete",
 *     "collection" = "/admin/config/build_hooks/frontend_environment"
 *   },
 *   config_export = {
 *     "id",
 *     "label",
 *     "weight",
 *     "provider",
 *     "plugin",
 *     "settings",
 *     "url",
 *     "deployment_strategy"
 *   },
 * )
 */
class FrontendEnvironment extends ConfigEntityBundleBase implements FrontendEnvironmentInterface, EntityWithPluginCollectionInterface {

  /**
   * The Frontend environment ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The plugin collection that holds the block plugin for this entity.
   *
   * @var \Drupal\block\BlockPluginCollection
   */
  protected $pluginCollection;

  /**
   * The plugin instance ID.
   *
   * @var string
   */
  protected $plugin;

  /**
   * The Frontend environment label.
   *
   * @var string
   */
  protected $label;

  /**
   * The Frontend environment deployment strategy.
   *
   * @var string
   */
  protected $deployment_strategy;

  /**
   * The url of the environment.
   *
   * @var string
   */
  protected $url;

  /**
   * The weight of the environment.
   *
   * @var int
   */
  protected $weight = 0;

  /**
   * The plugin instance settings.
   *
   * @var array
   */
  protected $settings = [];

  /**
   * {@inheritdoc}
   */
  public function getUrl() {
    return $this->url;
  }

  /**
   * {@inheritdoc}
   */
  public function getWeight() {
    return $this->weight;
  }

  /**
   * {@inheritdoc}
   */
  public function getDeploymentStrategy() {
    return $this->deployment_strategy;
  }

  /**
   * {@inheritdoc}
   */
  public function setDeploymentStrategy($deployment_strategy) {
    $this->deployment_strategy = $deployment_strategy;
  }

  /**
   * Encapsulates creation of the frontend environment's LazyPluginCollection.
   *
   * @return \Drupal\Component\Plugin\LazyPluginCollection
   *   The frontend environment's plugin collection.
   */
  protected function getPluginCollection() {
    if (!$this->pluginCollection) {
      $this->pluginCollection = new FrontendEnvironmentPluginCollection(\Drupal::service('plugin.manager.frontend_environment'), $this->plugin, $this->get('settings'), $this->id());
    }
    return $this->pluginCollection;
  }

  /**
   * {@inheritdoc}
   */
  public function getPlugin() {
    return $this->getPluginCollection()->get($this->plugin);
  }

  /**
   * {@inheritdoc}
   */
  public function getPluginCollections() {
    return [
      'settings' => $this->getPluginCollection(),
    ];
  }

}
