<?php

namespace Drupal\Tests\build_hooks_circleci\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Defines a class for testing configuration form of CircleV2 plugin.
 *
 * @group build_hooks_circleci
 */
class CircleV2FormTest extends BrowserTestBase {

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
    'build_hooks_circleci',
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
    $this->drupalGet('admin/config/build_hooks/frontend_environment/add/circleciv2');
    $assert = $this->assertSession();
    $assert->statusCodeEquals(200);
    $add = $assert->buttonExists('Add another parameter');
    $random = mb_strtolower($this->randomMachineName());
    $values = [
      'id' => $random,
      'label' => $random,
      'url' => 'http://example.com/' . $random,
      'deployment_strategy' => 'manual',
      'settings[token]' => $random,
      'settings[type]' => 'branch',
      'settings[project]' => 'foo/bar',
      'settings[reference]' => 'master',
      'settings[parameters][0][name]' => 'whiz',
      'settings[parameters][0][type]' => 'string',
      'settings[parameters][0][value]' => 'whang',
    ];
    foreach ($values as $name => $value) {
      $assert->fieldExists($name)->setValue($value);
    }
    $add->click();
    $values = [
      'settings[parameters][1][name]' => 'gizz',
      'settings[parameters][1][type]' => 'boolean',
      'settings[parameters][1][value]' => 'true',
    ];
    foreach ($values as $name => $value) {
      $assert->fieldExists($name)->setValue($value);
    }
    $add->click();
    $values = [
      'settings[parameters][2][name]' => 'sha',
      'settings[parameters][2][type]' => 'integer',
      'settings[parameters][2][value]' => '1',
    ];
    foreach ($values as $name => $value) {
      $assert->fieldExists($name)->setValue($value);
    }
    $add->click();
    $assert->fieldExists('settings[parameters][3][name]');
    $assert->buttonExists('Remove item 4')->click();
    $assert->fieldNotExists('settings[parameters][3][name]');
    $this->submitForm([], 'Save');
    /** @var \Drupal\build_hooks\Entity\FrontendEnvironmentInterface $environment */
    $environment = \Drupal::entityTypeManager()->getStorage('frontend_environment')->load($random);
    $this->assertNotEmpty($environment);
    $this->assertEquals([
      ['name' => 'whiz', 'value' => 'whang', 'type' => 'string'],
      ['name' => 'gizz', 'value' => TRUE, 'type' => 'boolean'],
      ['name' => 'sha', 'value' => 1, 'type' => 'integer'],
    ], $environment->get('settings')['parameters']);
  }

}
