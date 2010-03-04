<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCommunityTopicAclBuilder
 *
 * @package    OpenPNE
 * @subpackage acl
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.com>
 */
class opCommunityTopicAclBuilder extends opAclBuilder
{
  static protected
    $collection = array(),
    $resource = array();

  static public function clearCache()
  {
    self::$collection = array();
    self::$resource = array();
    opCommunityAclBuilder::clearCache();
  }

  static public function buildCollection($community, $targetMembers = array())
  {
    if (isset(self::$collection[$community->getId()]))
    {
      return self::$collection[$community->getId()];
    }

    $acl = opCommunityAclBuilder::buildResource($community, $targetMembers);
    $acl->addRole(new Zend_Acl_Role('writer'), 'member');

    if ($community->getConfig('topic_authority') === 'admin_only')
    {
      $acl->allow('admin', null, 'add');
    }
    else
    {
      $acl->allow('member', null, 'add');
    }

    if ('auth_commu_member' === $community->getConfig('public_flag'))
    {
      $acl->deny('guest', null, 'view');
      $acl->allow('member', null, 'view');
    }

    self::$collection[$community->getId()] = $acl;

    return $acl;
  }

  static public function buildResource($topic, $targetMembers)
  {
    if (isset(self::$resource[$topic->getId()]))
    {
      return self::$resource[$topic->getId()];
    }

    $acl = self::buildCollection($topic->getCommunity(), $targetMembers);
    $role = new Zend_Acl_Role($topic->getMemberId());
    if ($acl->hasRole($role) && $topic->getCommunity()->isPrivilegeBelong($topic->getMemberId()))
    {
      $acl->removeRole($role);
      $acl->addRole($role, 'writer');
    }

    $acl->allow('member', null, 'addComment');
    $acl->allow('admin', null, 'edit');
    $acl->allow('writer', null, 'edit');
    $acl->allow('admin', null, 'delete');
    $acl->allow('writer', null, 'delete');

    self::$resource[$topic->getId()] = $acl;

    return $acl;
  }
}
