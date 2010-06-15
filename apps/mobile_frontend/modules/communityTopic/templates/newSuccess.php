<?php op_mobile_page_title($community->getName(), __('Create a new topic')) ?>
<?php if ('example.com' !== sfConfig::get('op_mail_domain')): ?>
[i:106]<?php echo op_mail_to('community_topic_create', array('id' => $community->getId()), __('Post from E-mail')) ?><br>
<?php endif; ?>
<?php
op_include_form('formCommunityTopic', $form, array(
  'url' => url_for('communityTopic_create', $community),
  'button' => __('Create')
));
