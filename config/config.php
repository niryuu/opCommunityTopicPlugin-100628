<?php

$this->dispatcher->connect(
  'op_acl.unknown_community_public_flag',
  array('opHookCommunityPublicFlag', 'listenToUnknownCommunityPublicFlag')
);
