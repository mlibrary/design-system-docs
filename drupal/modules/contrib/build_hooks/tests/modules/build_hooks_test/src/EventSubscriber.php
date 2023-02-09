<?php

namespace Drupal\build_hooks_test;

use Drupal\build_hooks\Event\BuildTrigger;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Defines test event subscribers.
 */
class EventSubscriber implements EventSubscriberInterface {

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    return [
      BuildTrigger::class => 'onBuildTrigger',
    ];
  }

  /**
   * Reacts to build trigger event.
   *
   * @param \Drupal\build_hooks\Event\BuildTrigger $trigger
   *   Trigger.
   */
  public function onBuildTrigger(BuildTrigger $trigger) {
    if (($trigger->getFrontendEnvironment()->getPlugin()->getConfiguration()['whiz'] ?? '') === 'no deploy for you module') {
      $trigger->setShouldNotBuild(new TranslatableMarkup('No deploy for you'));
    }
  }

}
