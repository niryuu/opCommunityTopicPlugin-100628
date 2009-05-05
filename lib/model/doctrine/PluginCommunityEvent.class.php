<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginCommunityEvent
 * 
 * @package    opCommunityTopicPlugin
 * @subpackage CommunityEvent
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
abstract class PluginCommunityEvent extends BaseCommunityEvent
{
  public function isEditable($memberId)
  {
    if (!$this->getCommunity()->isPrivilegeBelong($memberId))
    {
      return false;
    }

    return ($this->getMemberId() === $memberId || $this->getCommunity()->isAdmin($memberId));
  }

  public function isCreatableCommunityEventComment($memberId)
  {
    return $this->getCommunity()->isPrivilegeBelong($memberId);
  }

  public function isEventMember($memberId)
  {
    return (bool)Doctrine::getTable('CommunityEventMember')->retrieveByEventIdAndMemberId($this->getId(), $memberId);
  }

  public function isEventModified()
  {
    $modified = $this->getModified();
    return (isset($modified['name']) || isset($modified['body']));
  }

  public function preSave()
  {
    $modified = $this->getModified();
    if ($this->isEventModified() && empty($modified['event_updated_at']))
    {
      $this->setEventUpdatedAt(date('Y-m-d H:i:s', time()));
    }
  }

  public function toggleEventMember($memberId)
  {
    if ($this->isClosed())
    {
      throw new opCommunityEventMemberAppendableException('This event has already been finished.');
    }

    if ($this->isExpired())
    {
      throw new opCommunityEventMemberAppendableException('This event has already been expired.');
    }

    if ($this->isEventMember($memberId))
    {
      $eventMember = Doctrine::getTable('CommunityEventMember')->retrieveByEventIdAndMemberId($this->getId(), $memberId);
      $eventMember->delete();
    }
    else
    {
      if ($this->isAtCapacity())
      {
        throw new opCommunityEventMemberAppendableException('This event has already been at capacity.');
      }

      $eventMember = new CommunityEventMember();
      $eventMember->setCommunityEventId($this->getId());
      $eventMember->setMemberId($memberId);
      $eventMember->save();
    }
  }

  public function isClosed()
  {
    return (time() > strtotime($this->getOpenDate()));
  }

  public function isExpired()
  {
    return (!is_null($this->getApplicationDeadline()) && time() > strtotime($this->getApplicationDeadline()));
  }

  public function isAtCapacity()
  {
    return (!is_null($this->getCapacity()) && $this->countCommunityEventMembers() >= $this->getCapacity());
  }
}
