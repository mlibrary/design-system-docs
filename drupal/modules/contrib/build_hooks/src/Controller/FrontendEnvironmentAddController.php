<?php

namespace Drupal\build_hooks\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for building the block instance add form.
 */
class FrontendEnvironmentAddController extends ControllerBase {

  /**
   * Add the Frontend environment form.
   *
   * @param string $plugin_id
   *   The plugin id of the frontend environment.
   *
   * @return array
   *   The form to add and configure a frontend environment entity.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function frontendEnvironmentAddConfigureForm($plugin_id) {
    // Create a frontend environment entity.
    $entity = $this->entityTypeManager()->getStorage('frontend_environment')->create(['plugin' => $plugin_id]);
    return $this->entityFormBuilder()->getForm($entity);
  }

}
