<?php

/**
 * @file
 * Contains post-update hooks.
 */

use Drupal\build_hooks\DeployLogger;
use Drupal\build_hooks\Entity\Deployment;

/**
 * Create deployment entities for undeployed items.
 */
function build_hooks_post_update_create_deployments_for_open_items(&$sandbox) {
  $environment_storage = \Drupal::entityTypeManager()->getStorage('frontend_environment');
  if (!isset($sandbox['progress'])) {
    // This must be the first run. Initialize the sandbox.
    $sandbox['progress'] = 0;
    $sandbox['ids'] = $environment_storage->getQuery()->execute();
    $sandbox['max'] = count($sandbox['ids']);
  }
  $id = array_shift($sandbox['ids']);
  $environment = $environment_storage->load($id);
  $last_deployment = \Drupal::state()->get('lastDeployForEnv' . $environment->id(), 0);
  $formatter = \Drupal::service('date.formatter');
  $deployment = Deployment::create([
    'label' => sprintf('Items changed since %s for %s', $formatter->format($last_deployment), $environment->label()),
    'environment' => $environment->id(),
    'status' => 0,
  ]);

  $query = \Drupal::database()->select('watchdog', 'w');
  $query->fields('w', [
    'wid', 'message', 'variables',
  ]);
  $query->condition('w.timestamp', $last_deployment, '>');
  $query->condition('w.type', DeployLogger::LOGGER_CHANNEL_NAME, '=');
  $result = $query->execute();
  $entity_types = array_values(array_filter(\Drupal::config('build_hooks.settings')->get('logging.entity_types')));
  $matches_by_entity_type = [];
  $deleted = [];
  foreach ($result as $row) {
    // This isn't ideal, but its the best we can do.
    $item = strtr($row->message, unserialize($row->variables));
    if (preg_match('/(?<bundle>.*): (?<title>.*) was (?<action>created|updated)\./', $item, $matches)) {
      foreach ($entity_types as $entity_type_id) {
        $storage = \Drupal::entityTypeManager()->getStorage($entity_type_id);
        $entity_type = \Drupal::entityTypeManager()->getDefinition($entity_type_id);
        if (!$entity_type->getKey('label')) {
          // Nothing we can do here.
          continue;
        }
        $query = $storage->getQuery()->condition($entity_type->getKey('label'), $matches['title']);
        if ($bundle_key = $entity_type->getKey('bundle')) {
          $query->condition($bundle_key, $matches['bundle']);
        }
        $matching_ids = array_values($query->execute());
        if (!$matching_ids) {
          continue;
        }
        $matches_by_entity_type[$entity_type_id] = array_merge($matches_by_entity_type[$entity_type_id] ?? [], $matching_ids);
        // We found a matching entry here.
        continue 2;
      }
    }
    if (preg_match('/(?<bundle>.*): (?<title>.*) was deleted\./', $item, $matches)) {
      foreach ($entity_types as $entity_type_id) {
        $entity_type = \Drupal::entityTypeManager()->getDefinition($entity_type_id);
        $bundles = \Drupal::service('entity_type.bundle.info')->getBundleInfo($entity_type_id);
        if (!in_array($matches['bundle'], array_keys($bundles), TRUE)) {
          continue;
        }
        // We can't do much here, the entity is deleted so all we can do is
        // match on bundle ID.
        $deleted[] = sprintf('%s (%s)', $matches['title'], $entity_type->getLabel());
        continue 2;
      }
    }
  }
  $deployment->contents = array_reduce(array_keys($matches_by_entity_type), function (array $carry, string $entity_type_id) use ($matches_by_entity_type) {
    $ids = $matches_by_entity_type[$entity_type_id];
    $carry = array_merge($carry, array_map(function ($id) use ($entity_type_id) {
      return [
        'target_id' => $id,
        'target_type' => $entity_type_id,
      ];
    }, array_unique($ids)));
    return $carry;
  }, []);
  $deployment->deleted = $deleted;
  $deployment->save();
  $sandbox['progress']++;
  $sandbox['#finished'] = empty($sandbox['max']) ? 1 : $sandbox['progress'] / $sandbox['max'];
}
