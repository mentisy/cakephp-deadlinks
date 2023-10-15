<?php
/**
 * @var \Cake\View\View $this
 * @var \Avolle\Deadlinks\Deadlinks\ResultSet[] $result
 */

use Cake\I18n\DateTime;
?>
Dead Links Scan Results run <?= DateTime::now()->format('Y-m-d H:i'); ?>

A total of <?= count($result); ?> tables were scanned.

<?php foreach ($result as $set): ?>
----- <?= $set->getTableName(); ?> -----
<?php if ($set->isEmpty()): ?>
No dead links found
<?php else: ?>

<?php foreach ($set->getResults() as $deadLink): ?>
PrimaryKey: <?= $deadLink->getPrimaryKey(); ?>

PrimaryKeyValue: <?= $deadLink->getPrimaryKeyValue(); ?>

Field: <?= $deadLink->getField(); ?>

Value: <?= $deadLink->getValue(); ?>


<?php endforeach; ?>
<?php endif; ?>
<?php endforeach; ?>
