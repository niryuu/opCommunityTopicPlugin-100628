<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * communityTopic actions.
 *
 * @package    OpenPNE
 * @subpackage communityTopic
 * @author     masabon
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class communityTopicActions extends sfActions
{
  public function preExecute()
  {
    $this->communityTopicId = $this->getRequestParameter('id');
    $this->communityTopic = CommunityTopicPeer::retrieveByPk($this->communityTopicId);

    if ($this->communityTopic)
    {
      $this->community = $this->communityTopic->getCommunity();
    } else {
      $this->community = new Community();
      $this->community->setId($this->getRequestParameter('community_id'));
    }
    if ($this->community)
    {
    $this->communityId = $this->community->getId();
    }

    $this->communityConfigTopicAuthority = CommunityConfigPeer::retrieveByNameAndCommunityId('topic_authority', $this->communityId);
    if ($this->communityConfigTopicAuthority && $this->communityConfigTopicAuthority->getValue() === 'admin_only')
    {
      $this->checkOwner = true;
    }
    else
    {
      $this->checkOwner = false;
    }
  }

 /**
  * Executes listCommunity action
  *
  * @param sfRequest $request A request object
  */
  public function executeListCommunity(sfWebRequest $request)
  {
    $this->community = $this->getRoute()->getObject();

    if ($this->community->getConfig('public_flag') === 'auth_commu_member')
    {
      $this->forward404Unless($this->community->isPrivilegeBelong($this->getUser()->getMemberId()));
    }

    $this->pager = CommunityTopicPeer::getCommunityTopicListPager($this->community->getId(), $request->getParameter('page'), 20);
  }

 /**
  * Executes show action
  *
  * @param sfRequest $request A request object
  */
  public function executeShow(sfWebRequest $request)
  {
    $this->communityTopic = $this->getRoute()->getObject();
    $this->community = $this->communityTopic->getCommunity();

    if ($this->community->getConfig('public_flag') === 'auth_commu_member')
    {
      $this->forward404Unless($this->community->isPrivilegeBelong($this->getUser()->getMemberId()));
    }

    $this->commentPager = CommunityTopicCommentPeer::getCommunityTopicCommentListPager($this->communityTopic->getId(), $request->getParameter('page'), 20);

    $this->form = new CommunityTopicCommentForm();
  }
/*
    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('community_topic_comment'));
      if ($this->form->isValid())
      {
        $communityTopicComment = $this->form->save();
        $this->redirect('communityTopic/detail?id='.$this->communityTopicId);
      }
    }
    */

 /**
  * Executes new action
  *
  * @param sfRequest $request A request object
  */
  public function executeNew(sfWebRequest $request)
  {
    $this->community = $this->getRoute()->getObject();

    if ($this->community->getConfig('topic_authority') === 'admin_only')
    {
      $this->forward404Unless($this->community->isAdmin($this->getUser()->getMemberId()));
    }
    else
    {
      $this->forward404Unless($this->community->isPrivilegeBelong($this->getUser()->getMemberId()));
    }

    $this->form = new CommunityTopicForm();
  }

 /**
  * Executes create action
  *
  * @param sfRequest $request A request object
  */
  public function executeCreate(sfWebRequest $request)
  {
    $this->community = $this->getRoute()->getObject();

    if ($this->community->getConfig('topic_authority') === 'admin_only')
    {
      $this->forward404Unless($this->community->isAdmin($this->getUser()->getMemberId()));
    }
    else
    {
      $this->forward404Unless($this->community->isPrivilegeBelong($this->getUser()->getMemberId()));
    }

    $this->form = new CommunityTopicForm();
    $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
    $this->form->getObject()->setCommunity($this->community);
    $this->form->bind($request->getParameter('community_topic'));
    if ($this->form->isValid())
    {
      $communityTopic = $this->form->save();
      $this->redirect($this->generateUrl('communityTopic_show', $communityTopic));
    }

    $this->setTemplate('new');
  }


 /**
  * Executes edit action
  *
  * @param sfRequest $request A request object
  */
  public function executeEdit($request)
  {
    $this->community->checkPrivilegeBelong($this->getUser()->getMemberId());
    if ($this->checkOwner)
    {
      $this->community->checkPrivilegeOwner($this->getUser()->getMemberId());
    }

    $this->form = new CommunityTopicForm($this->communityTopic, array('community_id' => $this->communityId));

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('community_topic'));
      if ($this->form->isValid())
      {
        $communityTopic = $this->form->save();
        $this->redirect('community/home?id='.$this->communityId);
      }
    }
  }


 /**
  * Executes delete action
  *
  * @param sfRequest $request A request object
  */
  public function executeDelete($request)
  {
    $this->community->checkPrivilegeBelong($this->getUser()->getMemberId());
    if ($this->checkOwner)
    {
      $this->community->checkPrivilegeOwner($this->getUser()->getMemberId());
    }

    $this->comments = CommunityTopicCommentPeer::retrieveByCommunityTopicId($this->communityTopicId);

    foreach ($this->comments as $comment)
    {
      echo $comment->delete();
    }

    $this->communityTopic->delete();
    $this->redirect('community/home?id='.$this->communityId);
  }
}
