<?php

class opHookCommunityPublicFlag
{
  static public function listenToUnknownCommunityPublicFlag(sfEvent $event, $acl)
  {
    if ('auth_commu_member' === $event['public_flag'])
    {
      $acl->allow('guest', null, 'view');
    }

    return $acl;
  }
}
