<?php

namespace Drupal\Tests\build_hooks\Functional;

use Drupal\build_hooks\Entity\FrontendEnvironment;
use Drupal\build_hooks\Entity\FrontendEnvironmentInterface;
use Drupal\build_hooks\TriggerInterface;
use Drupal\Core\Url;
use Drupal\entity_test\Entity\EntityTest;
use Drupal\Tests\BrowserTestBase;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines a class for testing build hooks UI.
 *
 * @group build_hooks
 */
class UiTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'build_hooks',
    'build_hooks_test',
    'block',
    'system',
    'user',
    'views',
    'entity_test',
    'toolbar',
  ];

  /**
   * Test build hooks UI.
   */
  public function testBuildHooksUi() {
    $this->drupalPlaceBlock('system_messages_block');
    $this->drupalPlaceBlock('local_actions_block');
    $this->drupalPlaceBlock('page_title_block');

    $this->assertThatAnonymousUsersCannotAccessSettingsForm();
    $this->assertThatAnonymousUsersCannotAccessPluginTypesList();
    $this->assertThatAnonymousUsersCannotAccessEnviromentSettingsPages();

    $this->drupalLogin($this->createUser([
      'administer site configuration',
      'access administration pages',
      'access content',
      'access toolbar',
      'trigger deployments',
      'manage frontend environments',
      'access site reports',
      'view test entity',
    ]));
    $this->assertSettingsFormFunctionality();
    $this->assertPluginTypesListFunctionality();
    $this->assertAddEnvironmentForPluginFunctionality();
    $environment = $this->assertThatAdminCanAddFrontEndEnvironment();
    $environment = $this->assertThatAdminCanEditFrontEndEnvironment($environment);
    $this->assertFrontEndEnvironmentDeploymentFormFunctionality($environment);
    $this->assertToolbarIntegration($environment, 2);
    $this->assertInactiveEnvironmentToolbarIntegration($environment);
    $this->assertThatAdminCanDeleteFrontEndEnvironment($environment);
  }

  /**
   * Assert that anonymous users can't access privileged pages.
   */
  private function assertThatAnonymousUsersCannotAccessSettingsForm() {
    $this->drupalGet(Url::fromRoute('build_hooks.hook_form'));
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Assert that anonymous users can't access privileged pages.
   */
  private function assertThatAnonymousUsersCannotAccessPluginTypesList() {
    $this->drupalGet(Url::fromRoute('build_hooks.frontend_environment_plugin_types'));
    $this->assertSession()->statusCodeEquals(403);
  }

  /**
   * Assert that anonymous users can't access privileged pages.
   */
  private function assertThatAnonymousUsersCannotAccessEnviromentSettingsPages() {
    $assert = $this->assertSession();
    $this->drupalGet(Url::fromRoute('entity.frontend_environment.collection'));
    $assert->statusCodeEquals(403);

    $title = $this->randomMachineName();
    $environment = FrontendEnvironment::create([
      'id' => 'foo',
      'label' => $title,
      'settings' => [],
      'plugin' => 'build_hooks_test',
      'deployment_strategy' => TriggerInterface::DEPLOYMENT_STRATEGY_ENTITYSAVE,
    ]);
    $environment->save();
    $this->drupalGet($environment->toUrl());
    $assert->statusCodeEquals(403);
    $this->drupalGet($environment->toUrl('edit-form'));
    $assert->statusCodeEquals(403);
    $this->drupalGet($environment->toUrl('delete-form'));
    $assert->statusCodeEquals(403);
    $environment->delete();
  }

  /**
   * Assert settings form functionality.
   */
  private function assertSettingsFormFunctionality() {
    $this->drupalGet(Url::fromRoute('build_hooks.hook_form'));
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $assert->fieldNotExists('logged_entity_types[build_hooks_deployment]');
    $this->submitForm([
      'logged_entity_types[entity_test]' => TRUE,
    ], 'Save configuration');
    $assert->pageTextContains('The configuration options have been saved.');
    $this->assertEquals([
      'entity_test',
    ], $this->config('build_hooks.settings')->get('logging.entity_types'));
  }

  /**
   * Assert plugin-types controller functionality.
   */
  private function assertPluginTypesListFunctionality() {
    $this->drupalGet(Url::fromRoute('build_hooks.frontend_environment_plugin_types'));
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Frontend environment types');
    $assert->linkExists('Add new environment');
    $assert->pageTextContains('Generic');
    $assert->pageTextContains('Test environment.');
  }

  /**
   * Assert add links work.
   */
  private function assertAddEnvironmentForPluginFunctionality() {
    $assert = $this->assertSession();
    $query = $assert->buildXPathQuery('//a[contains(@href, :href)]', [
      ':href' => Url::fromRoute('build_hooks.admin_add', [
        'plugin_id' => 'build_hooks_test',
      ])->toString(),
    ]);
    $link = $assert->elementExists('xpath', $query);
    $link->click();
    $assert->statusCodeEquals(200);
    $assert->pageTextContains('Add new frontend environment');
  }

  /**
   * Asserts admin can create new environment.
   *
   * @return \Drupal\build_hooks\Entity\FrontendEnvironmentInterface
   *   Created entity.
   */
  private function assertThatAdminCanAddFrontEndEnvironment() : FrontendEnvironmentInterface {
    $random = mb_strtolower($this->randomMachineName());
    $whiz = $this->randomMachineName(2);
    // Try to submit the form with whiz length 2 characters and a long ID.
    $this->submitForm([
      'id' => mb_strtolower($this->randomMachineName(34)),
      'label' => $random,
      'url' => 'http://example.com/' . $random,
      'deployment_strategy' => TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL,
      'settings[whiz]' => $whiz,
      ], 'Save');
    $assert = $this->assertSession();
    $assert->pageTextContains('Whiz must contains minimum 3 characters.');
    $assert->pageTextContains('Machine-readable name cannot be longer than 32 characters but is currently 34 characters long.');
    $this->submitForm([
      'id' => $random,
      'label' => $random,
      'url' => 'http://example.com/' . $random,
      'deployment_strategy' => TriggerInterface::DEPLOYMENT_STRATEGY_MANUAL,
      'settings[whiz]' => $random,
    ], 'Save');
    $assert->pageTextContains('The frontend environment configuration has been saved.');
    $environment = \Drupal::entityTypeManager()->getStorage('frontend_environment')->load($random);
    $this->assertNotEmpty($environment);
    $this->assertEquals($random, $environment->label());
    /** @var \Drupal\build_hooks\Plugin\FrontendEnvironmentInterface $plugin */
    $plugin = $environment->getPlugin();
    $this->assertEquals($random, $plugin->getConfiguration()['whiz']);
    $this->assertEquals('build_hooks_test', $plugin->getPluginId());
    return $environment;
  }

  /**
   * Tests editing an environment.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   *
   * @return \Drupal\build_hooks\Entity\FrontendEnvironmentInterface
   *   Edited entity.
   */
  private function assertThatAdminCanEditFrontEndEnvironment(FrontendEnvironmentInterface $environment) : FrontendEnvironmentInterface {
    $this->drupalGet($environment->toUrl('edit-form'));
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $new_name = $this->randomMachineName();
    $this->submitForm([
      'label' => $new_name,
    ], 'Save');
    $assert->pageTextContains('The frontend environment configuration has been saved.');
    $environment = \Drupal::entityTypeManager()->getStorage('frontend_environment')->loadUnchanged($environment->id());
    $this->assertNotEmpty($environment);
    $this->assertEquals($new_name, $environment->label());
    return $environment;
  }

  /**
   * Tests deployment form functionality.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   */
  private function assertFrontEndEnvironmentDeploymentFormFunctionality(FrontendEnvironmentInterface $environment) {
    $label = $this->randomMachineName();
    $entity = EntityTest::create([
      'name' => $label,
    ]);
    $entity->save();
    $this->assertToolbarIntegration($environment, 1);
    $label2 = $this->randomMachineName();
    $entity2 = EntityTest::create([
      'name' => $label2,
    ]);
    $entity2->save();
    $entity2->delete();
    $this->drupalGet(Url::fromRoute('build_hooks.deployment_form', [
      'frontend_environment' => $environment->id(),
    ]));
    $assert = $this->assertSession();
    $assert->pageTextContains($environment->label() . ' environment deployment');
    $assert->linkExists('http://example.com/' . $environment->id());
    $assert->pageTextContains('Changelog');
    $assert->linkExists($label);
    $assert->pageTextContains('Deployment contents');
    $assert->pageTextContains('Deleted items');
    $assert->pageTextContains(sprintf('%s (Test entity)', $label2));
    $assert->elementExists('css', 'h3:contains("Hi there")');
  }

  /**
   * Tests admin can delete environments.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   */
  private function assertThatAdminCanDeleteFrontEndEnvironment(FrontendEnvironmentInterface $environment) {
    $this->drupalGet($environment->toUrl('delete-form'));
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $assert->pageTextContains(sprintf('Are you sure you want to delete %s', $environment->label()));
    $this->submitForm([], 'Delete');
    $assert->pageTextContains(sprintf('The frontend environment %s was deleted', $environment->label()));
  }

  /**
   * Tests toolbar integration.
   *
   * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
   *   Environment.
   * @param int $expected_count
   *   Expected count.
   */
  private function assertToolbarIntegration(FrontendEnvironmentInterface $environment, int $expected_count) {
    $this->drupalGet(Url::fromRoute('<front>'));
    if ($expected_count > 1) {
      $this->assertSession()->linkExists(sprintf('%s (2 changes)', $environment->label()));
    }
    else {
      $this->assertSession()->linkExists(sprintf('%s (1 change)', $environment->label()));
    }
  }

  /**
  * Tests inactive environment toolbar integration.
  *
  * @param \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment
  *   Environment.
  */
  private function assertInactiveEnvironmentToolbarIntegration(FrontendEnvironmentInterface $environment) {
    $environment->set('status', TRUE);
    $environment->save();
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->linkExists(sprintf('%s (2 changes)', $environment->label()));
    $environment->set('status', FALSE);
    $environment->save();
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->linkNotExists(sprintf('%s (2 changes)', $environment->label()));
    $environment->set('status', TRUE);
    $environment->save();
    $this->drupalGet(Url::fromRoute('<front>'));
    $this->assertSession()->linkExists(sprintf('%s (2 changes)', $environment->label()));
  }

}
