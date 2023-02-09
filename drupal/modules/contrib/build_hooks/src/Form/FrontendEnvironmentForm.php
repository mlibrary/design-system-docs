<?php

namespace Drupal\build_hooks\Form;

use Drupal\build_hooks\Plugin\FrontendEnvironmentInterface;
use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\SubformState;
use Drupal\Core\Plugin\PluginFormFactoryInterface;
use Drupal\Core\Plugin\PluginWithFormsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\build_hooks\TriggerInterface;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Class StaticFrontEnvironmentForm.
 */
class FrontendEnvironmentForm extends EntityForm {

  /**
   * The plugin form manager.
   *
   * @var \Drupal\Core\Plugin\PluginFormFactoryInterface
   */
  protected $pluginFormFactory;

  /**
   * FrontendEnvironmentForm constructor.
   *
   * @param \Drupal\Core\Plugin\PluginFormFactoryInterface $plugin_form_manager
   *   The plugin form manager.
   */
  public function __construct(PluginFormFactoryInterface $plugin_form_manager) {
    $this->pluginFormFactory = $plugin_form_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin_form.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $entity */
    $entity = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $entity->label(),
      '#description' => $this->t("Label for the Frontend environment."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#maxlength' => EntityTypeInterface::BUNDLE_MAX_LENGTH,
      '#default_value' => $entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\build_hooks\Entity\FrontendEnvironment::load',
      ],
      '#disabled' => !$entity->isNew(),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#title' => $this->t('Url'),
      '#maxlength' => 255,
      '#default_value' => $entity->getUrl(),
      '#description' => $this->t("Url at which this environment is available for viewing."),
      '#required' => TRUE,
    ];

    $deployment_strategy_allowed_values = [
      TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL => $this->t('<strong>Manually only</strong>: deploys are triggered only by submitting the deployment form.'),
      TriggerInterface::DEPLOYMENT_STRATEGY_CRON => $this->t('<strong>On cron</strong>: a deployment will be triggered for this environment at each cron run.'),
      TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE => $this->t('<strong>When content is updated</strong>: deploy this environment when one of the logged entities is added, modified or deleted.'),
    ];

    $form['deployment_strategy'] = [
      '#type' => 'radios',
      '#title' => $this->t('Deployment strategy.'),
      '#default_value' => $entity->getDeploymentStrategy() ? $entity->getDeploymentStrategy() : TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL,
      '#options' => $deployment_strategy_allowed_values,
      '#description' => $this->t('You can choose here how the deployments should be triggered for this environment.'),
    ];

    $form['weight'] = [
      '#type' => 'number',
      '#title' => $this->t('Weight'),
      '#max' => 100,
      '#min' => -100,
      '#size' => 3,
      '#default_value' => $entity->getWeight() ? $entity->getWeight() : 0,
      '#description' => $this->t("Set the weight, lighter environments will be rendered first in the toolbar."),
      '#required' => TRUE,
    ];

    $form['#tree'] = TRUE;
    $form['settings'] = [];
    $subform_state = SubformState::createForSubform($form['settings'], $form, $form_state);
    $form['settings'] = $this->getPluginForm($entity->getPlugin())
      ->buildConfigurationForm($form['settings'], $subform_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $entity */
    $entity = $this->entity;

    $sub_form_state = SubformState::createForSubform($form['settings'], $form, $form_state);
    // Call the plugin validate handler.
    $block = $entity->getPlugin();
    $this->getPluginForm($block)
      ->validateConfigurationForm($form, $sub_form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $entity */
    $entity = $this->entity;

    $sub_form_state = SubformState::createForSubform($form['settings'], $form, $form_state);
    // Call the plugin submit handler.
    $block = $entity->getPlugin();
    $this->getPluginForm($block)
      ->submitConfigurationForm($form, $sub_form_state);

    $entity->save();

    $this->messenger()->addStatus($this->t('The frontend environment configuration has been saved.'));
    $form_state->setRedirectUrl($entity->toUrl('collection'));
  }

  /**
   * {@inheritdoc}
   */
  protected function getPluginForm(FrontendEnvironmentInterface $frontendEnvironment) {
    if ($frontendEnvironment instanceof PluginWithFormsInterface) {
      return $this->pluginFormFactory->createInstance($frontendEnvironment, 'configure');
    }
    return $frontendEnvironment;
  }

}
