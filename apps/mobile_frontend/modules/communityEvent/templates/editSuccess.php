<?php
$options = array(
  'title' => __('Edit the event'),
  'url' => url_for('communityEvent_update', $communityEvent),
  'button' => __('Edit')
);
op_include_form('formCommunityEvent', $form, $options);
?>

<?php
$options = array(
  'title' => __('Delete the event and comments'),
  'button' => __('Delete'),
  'url' => url_for('communityEvent_delete_confirm', $communityEvent),
  'method' => 'get'
);
op_include_parts('buttonBox', 'toDelete', $options);
?>
