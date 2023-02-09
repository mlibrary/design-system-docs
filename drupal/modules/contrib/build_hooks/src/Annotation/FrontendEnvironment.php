<?php

namespace Drupal\build_hooks\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Frontend environment item annotation object.
 *
 * @see \Drupal\build_hooks\Plugin\FrontendEnvironmentManager
 * @see plugin_api
 *
 * @Annotation
 */
class FrontendEnvironment extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $description;

}
