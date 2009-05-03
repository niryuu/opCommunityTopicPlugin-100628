<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginCommunityTopic
 * 
 * @package    opCommunityTopicPlugin
 * @subpackage CommunityTopic
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class PluginCommunityTopic extends BaseCommunityTopic
{
  public function isEditable($memberId)
  {
    if (!$this->getCommunity()->isPrivilegeBelong($memberId))
    {
      return false;
    }

    return ($this->getMemberId() === $memberId || $this->getCommunity()->isAdmin($memberId));
  }

  public function isCreatableCommunityTopicComment($memberId)
  {
    return $this->getCommunity()->isPrivilegeBelong($memberId);
  }

  public function isTopicModified()
  {
    $modified = $this->getModified();
    return ($modified['name'] || $modified['body']);
  }

  public function preSave()
  {
    $modified = $this->getModified();
    if ($this->isTopicModified() && !$modified['topic_updated_at'])
    {
      $this->setTopicUpdatedAt(time());
    }
  }
}
