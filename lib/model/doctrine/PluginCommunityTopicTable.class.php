<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginCommunityTopicTable
 * 
 * @package    opCommunityTopicPlugin
 * @subpackage CommunityTopicComment
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PluginCommunityTopicTable extends Doctrine_Table
{
  public function retrieveByCommunityId($communityId)
  {
    return $this->createQuery()
      ->where('community_id = ?', $communityId)
      ->execute();
  }

  public function getTopics($communityId, $limit = 5)
  {
    return $this->createQuery()
      ->where('community_id = ?', $communityId)
      ->limit($limit)
      ->orderBy('updated_at')
      ->execute();
  }

  public function getCommunityTopicListPager($communityId, $page = 1, $size = 20)
  {
    $q = $this->createQuery()
      ->where('community_id', $communityId)
      ->orderBy('updated_at');
    $pager = new sfDoctrinePager('CommunityTopic', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }

  public function retrivesByMemberId($memberId, $limit = 5)
  {
    $communityIds = Doctrine::getTable('Community')->getIdsByMemberId($memberId);
    return $this->createQuery()
      ->whereIn('community_id', $communityIds)
      ->limit($limit)
      ->orderBy('updated_at')
      ->execute();
  }

  public function getRecentlyTopicListPager($memberId, $page = 1, $size = 50)
  {
    $communityIds = Doctrine::getTable('Community')->getIdsByMemberId($memberId);
    $q = $this->createQuery()
      ->whereIn('community_id', $communityIds)
      ->orderBy('updated_at');

    $pager = new sfDoctrinePager('CommunityTopic', $size);
    $pager->setQuery($q);
    $pager->setPage($page);
    $pager->init();

    return $pager;
  }
}
