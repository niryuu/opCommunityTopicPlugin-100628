<?php
$options = array();
$options['title'] = 'トピック編集';
$options['url'] = '@communityTopic_update?id='.$communityTopic->getId();
op_include_form('formCommunityTopic', $form, $options);
?>
