<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginCommunityTopicComment
 * 
 * @package    opCommunityTopicPlugin
 * @subpackage CommunityTopicCommnet
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class PluginCommunityTopicComment extends BaseCommunityTopicComment
{
  public function isDeletable($memberId)
  {
    return ($this->getMemberId() === $memberId || $this->getCommunityTopic()->isEditable($memberId));
  }

  public function preSave()
  {
    if ($this->isNew() && !$this->isColumnModified(CommunityTopicCommentPeer::NUMBER))
    {
      $this->setTopicUpdatedAt(time());
      $this->setNumber(Doctrine::getTable('CommunityTopicComment')->getMaxNumber($this->getCommunityTopicId()) + 1);
    }
  }
}
