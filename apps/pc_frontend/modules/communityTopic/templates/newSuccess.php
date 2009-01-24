<?php
$options = array();
if ($form->isNew()) {
  $options['title'] = 'トピック作成';
  $options['url'] = '@communityTopic_create?id='.$community->getId();
} else {
  $options['title'] = 'トピック編集';
  $options['url'] = 'communityTopic/edit?id='.$communityTopic->getId();
}
op_include_form('formCommunityTopic', $form, $options);
?>
