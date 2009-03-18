<?php if (count($communityTopic)): ?>
<div id="homeRecentList_<?php echo $gadget->getId() ?>" class="dparts homeRecentList"><div class="parts">
<div class="partsHeading"><h3><?php echo __('Recently Posted Community Topics') ?></h3></div>
<div class="block">
<ul class="articleList">
<?php foreach ($communityTopic as $topic): ?>
<li><span class="date"><?php echo op_format_date($topic->getUpdatedAt(), 'XShortDateJa') ?></span><?php echo link_to(op_truncate($topic->getName(), 36).' ('.$topic->countCommunityTopicComments().')', 'communityTopic_show', $topic).' ('.$topic->getCommunity()->getName().')' ?></li>
<?php endforeach; ?>
</ul>
</div>
</div></div>
<?php endif; ?>
