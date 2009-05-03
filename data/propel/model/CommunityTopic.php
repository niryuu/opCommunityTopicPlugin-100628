<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class CommunityTopic extends BaseCommunityTopic
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
    return (
      $this->isColumnModified(CommunityTopicPeer::NAME) ||
      $this->isColumnModified(CommunityTopicPeer::BODY)
    );
  }

  public function save(PropelPDO $con = null)
  {
    if ($this->isTopicModified() && !$this->isColumnModified(CommunityTopicPeer::TOPIC_UPDATED_AT))
    {
      $this->setTopicUpdatedAt(time());
    }
    
    parent::save($con);
  }
}