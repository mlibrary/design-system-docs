<?php

namespace Drupal\build_hooks\Plugin;

use Drupal\build_hooks\Event\BuildTrigger;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Component\Transliteration\TransliterationInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginWithFormsInterface;
use Drupal\Core\Plugin\PluginWithFormsTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Psr\Http\Message\ResponseInterface;

/**
 * Base class for Frontend environment plugins.
 */
abstract class FrontendEnvironmentBase extends PluginBase implements FrontendEnvironmentInterface, PluginWithFormsInterface {

  use PluginWithFormsTrait;
  use StringTranslationTrait;

  /**
   * The transliteration service.
   *
   * @var \Drupal\Component\Transliteration\TransliterationInterface
   */
  protected $transliteration;

  /**
   * {@inheritdoc}
   */
  public function label() {
    if (!empty($this->configuration['label'])) {
      return $this->configuration['label'];
    }
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->setConfiguration($configuration);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfiguration() {
    return $this->configuration;
  }

  /**
   * {@inheritdoc}
   */
  public function setConfiguration(array $configuration) {
    $this->configuration = NestedArray::mergeDeep(
      $this->baseConfigurationDefaults(),
      $this->defaultConfiguration(),
      $configuration
    );
  }

  /**
   * Returns generic default configuration for frontend environment plugins.
   *
   * @return array
   *   An associative array with the default configuration.
   */
  protected function baseConfigurationDefaults() {
    return [
      'id' => $this->getPluginId(),
      'label' => '',
      'provider' => $this->pluginDefinition['provider'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function setConfigurationValue($key, $value) {
    $this->configuration[$key] = $value;
  }

  /**
   * {@inheritdoc}
   */
  public function calculateDependencies() {
    $dependencies = [];
    $definition = $this->getPluginDefinition();
    $dependencies['module'][] = $definition['provider'];
    return $dependencies;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $definition = $this->getPluginDefinition();
    $form['provider'] = [
      '#type' => 'value',
      '#value' => $definition['provider'],
    ];

    // Add plugin-specific settings for this frontend environment type.
    $form += $this->frontEndEnvironmentForm($form, $form_state);
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentForm($form, FormStateInterface $form_state) {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function validateConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->frontEndEnvironmentFormValidate($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentFormValidate($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    if (!$form_state->getErrors()) {
      $this->configuration['label'] = $form_state->getValue('label');
      $this->configuration['provider'] = $form_state->getValue('provider');
      $this->frontEndEnvironmentSubmit($form, $form_state);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function frontEndEnvironmentSubmit($form, FormStateInterface $form_state) {}

  /**
   * {@inheritdoc}
   */
  public function getMachineNameSuggestion() {
    $definition = $this->getPluginDefinition();
    $admin_label = $definition['admin_label'];
    $transliterated = $this->transliteration()
      ->transliterate($admin_label, LanguageInterface::LANGCODE_DEFAULT, '_');
    $transliterated = mb_strtolower($transliterated);

    $transliterated = preg_replace('@[^a-z0-9_.]+@', '', $transliterated);

    return $transliterated;
  }

  /**
   * Wraps the transliteration service.
   *
   * @return \Drupal\Component\Transliteration\TransliterationInterface
   *   The transliteration service.
   */
  protected function transliteration() {
    if (!$this->transliteration) {
      $this->transliteration = \Drupal::transliteration();
    }
    return $this->transliteration;
  }

  /**
   * Sets the transliteration service.
   *
   * @param \Drupal\Component\Transliteration\TransliterationInterface $transliteration
   *   The transliteration service.
   */
  public function setTransliteration(TransliterationInterface $transliteration) {
    $this->transliteration = $transliteration;
  }

  /**
   * {@inheritdoc}
   */
  public function deploymentWasTriggered(ResponseInterface $response): bool {
    return in_array($response->getStatusCode(), [200, 201], FALSE);
  }

  /**
   * {@inheritdoc}
   */
  public function preDeploymentTrigger(BuildTrigger $trigger) : void {
    // Nil-op.
  }

}
