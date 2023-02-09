<?php

namespace Drupal\Tests\build_hooks_github\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Defines a test for the configuration form of the github workflows plugin.
 *
 * @group build_hooks_github
 */
class GithubFormTest extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'dblog',
    'views',
    'build_hooks',
    'build_hooks_github',
  ];

  /**
   * Tests configuration form.
   */
  public function testConfigureForm() {
    $this->drupalLogin($this->createUser([
      'administer site configuration',
      'access administration pages',
      'access content',
      'trigger deployments',
      'manage frontend environments',
      'access site reports',
    ]));
    $this->drupalGet('admin/config/build_hooks/frontend_environment/add/github');
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $random = mb_strtolower($this->randomMachineName());
    $values = [
      'id' => $random,
      'label' => $random,
      'url' => 'http://example.com/' . $random,
      'deployment_strategy' => 'manual',
      'settings[build_hook_url]' => 'https://api.github.com/repos/' . $random . '-build_hook_url',
      'settings[branch]' => 'branch',
    ];
    foreach ($values as $name => $value) {
      $assert->fieldExists($name)->setValue($value);
    }
    $this->submitForm([], 'Save');
    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment */
    $environment = \Drupal::entityTypeManager()->getStorage('frontend_environment')->load($random);
    $this->assertNotEmpty($environment);
    $settings = $environment->get('settings');
    $this->assertEquals($settings['branch'], 'branch');
    $this->assertEquals($settings['build_hook_url'], 'https://api.github.com/repos/' . $random . '-build_hook_url');
  }

}
