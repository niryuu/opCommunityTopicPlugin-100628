<?php slot('submenu') ?>
<?php include_partial('submenu') ?>
<?php end_slot() ?>

<?php slot('title', __('Delete Community Event Member')); ?>

<p><?php echo __('Do you really want to delete this event member?') ?></p>

<form action="<?php url_for('communitytopic/eventMemberDelete?id='.$eventMember->getId()) ?>" method="post">
<?php include_partial('communitytopic/eventMemberInfo', array(
  'eventMember' => $eventMember,
  'moreInfo' => array('<input type="submit" value="' . __('Delete') . '" />')
)); ?>
</form>

