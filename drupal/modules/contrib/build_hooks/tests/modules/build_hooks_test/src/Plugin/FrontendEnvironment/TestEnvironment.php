<?php

namespace Drupal\build_hooks_test\Plugin\FrontendEnvironment;

use Drupal\build_hooks\BuildHookDetails;
use Drupal\build_hooks\Event\BuildTrigger;
use Drupal\build_hooks\Plugin\FrontendEnvironmentBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Defines a test environment plugin.
 *
 * @FrontendEnvironment(
 *  id = "build_hooks_test",
 *  label = "Test environment",
 *  description = "Test environment."
 * )
 */
class TestEnvironment extends FrontendEnvironmentBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return parent::defaultConfiguration() + ['whiz' => 'whang'];
  }

  /**
   * {@inheritdoc}
   */
  public function getBuildHookDetails() {
    $details = new BuildHookDetails();
    $details->setOptions([
      'hi' => 'there',
    ]);
    $details->setMethod('POST');
    $details->setUrl('http://example.com?whiz=' . $this->configuration['whiz']);
    return $details;
  }

  /**
   * {@inheritdoc}
   */
  public function getAdditionalDeployFormElements(FormStateInterface $form_state) {
    return [
      [
        '#markup' => '<h3>Hi there</h3>',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm(
    $form,
    FormStateInterface $form_state
  ) {
    return parent::frontEndEnvironmentForm($form, $form_state) + [
      'whiz' => [
        '#type' => 'textfield',
        '#title' => $this->t('Whiz?'),
        '#default_value' => $this->configuration['whiz'],
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentFormValidate(
    $form,
    FormStateInterface $form_state
  ) {
    parent::frontEndEnvironmentFormValidate($form, $form_state);
    $whiz = $form_state->getValue('whiz');
    if (strlen($whiz) < 3) {
      $form_state->setErrorByName('whiz', $this->t('Whiz must contains minimum 3 characters.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit(
    $form,
    FormStateInterface $form_state
  ) {
    parent::frontEndEnvironmentSubmit($form, $form_state);
    $this->configuration['whiz'] = $form_state->getValue('whiz');
  }

  /**
   * {@inheritdoc}
   */
  public function preDeploymentTrigger(BuildTrigger $trigger): void {
    if ($this->configuration['whiz'] === 'no deploy for you') {
      $trigger->setShouldNotBuild(new TranslatableMarkup('No deploy today'));
    }
    if ($trigger->getDeployment()->contents->entity) {
      $entity = $trigger->getDeployment()->contents->entity;
      if ($entity->label() === "can't let you do that dave") {
        $trigger->setShouldNotBuild(new TranslatableMarkup('No deploy for this item'));
      }
    }
  }

}
