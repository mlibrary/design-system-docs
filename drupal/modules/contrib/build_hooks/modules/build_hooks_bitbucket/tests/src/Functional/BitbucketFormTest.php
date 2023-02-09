<?php

namespace Drupal\Tests\build_hooks_bitbucket\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Defines a test for the configuration form of the bitbucket pipelines plugin.
 *
 * @group build_hooks_bitbucket
 */
class BitbucketFormTest extends BrowserTestBase {

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
    'build_hooks_bitbucket',
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
    $this->drupalGet('admin/config/build_hooks/frontend_environment/add/bitbucket');
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $random = mb_strtolower($this->randomMachineName());
    $values = [
      'id' => $random,
      'label' => $random,
      'url' => 'http://example.com/' . $random,
      'deployment_strategy' => 'manual',
      'settings[repo][workspace]' => $random . '-workspace',
      'settings[repo][slug]' => $random . '-slug',
      'settings[ref][type]' => 'branch',
      'settings[ref][name]' => $random . '-ref',
      'settings[selector][type]' => 'pull-requests',
      'settings[selector][name]' => $random . '-selector',
    ];
    foreach ($values as $name => $value) {
      $assert->fieldExists($name)->setValue($value);
    }
    $this->submitForm([], 'Save');
    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment */
    $environment = \Drupal::entityTypeManager()->getStorage('frontend_environment')->load($random);
    $this->assertNotEmpty($environment);
    $settings = $environment->get('settings');
    $this->assertEquals($settings['selector']['type'], 'pull-requests');
    $this->assertEquals($settings['selector']['name'], $random . '-selector');
    $this->assertEquals($settings['ref']['type'], 'branch');
    $this->assertEquals($settings['ref']['name'], $random . '-ref');
    $this->assertEquals($settings['repo']['workspace'], $random . '-workspace');
    $this->assertEquals($settings['repo']['slug'], $random . '-slug');
  }

}
