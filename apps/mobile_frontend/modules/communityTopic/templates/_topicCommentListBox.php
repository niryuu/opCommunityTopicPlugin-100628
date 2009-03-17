<?php if (count($communityTopic)): ?>
<?php
$list = array();
foreach ($communityTopic as $topic)
{
  $list[] = sprintf("[%s] %s<br>%s",
    op_format_date($topic->getUpdatedAt(), 'XShortDate'),
    $topic->getCommunity()->getName(),
    link_to(sprintf("%s(%d)",
        op_truncate($topic->getName(), 28),
        $topic->countCommunityTopicComments()
      ),'communityTopic_show', $topic
    )
  );
}
$options = array(
  'title' => __('Recently Posted Community Topics'),
  'border' => true,
);
op_include_list('communityList', $list, $options);
?>

<?php endif; ?>
