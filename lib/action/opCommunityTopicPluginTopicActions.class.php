<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * opCommunityTopicPluginTopicActions
 *
 * @package    OpenPNE
 * @subpackage action
 * @author     masabon
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 * @author     Shogo Kawahara <kawahara@tejimaya.net>
 */

class opCommunityTopicPluginTopicActions extends sfActions
{
  /**
   * preExecute
   */
  public function preExecute()
  {
    $object = $this->getRoute()->getObject();

    if ($object instanceof Community)
    {
      $this->community = $object;
    }
    elseif ($object instanceof CommunityTopic)
    {
      $this->communityTopic = $object;
      $this->community = $this->communityTopic->getCommunity();
    }
  }

  /**
   * Executes listCommunity action
   *
   * @param sfRequest $request A request object
   */
  public function executeListCommunity($request)
  {
    $this->forward404Unless($this->community->isViewableCommunityTopic($this->getUser()->getMemberId()));

    if (!$this->size)
    {
      $this->size = 20;
    }

    $this->pager = CommunityTopicPeer::getCommunityTopicListPager($this->community->getId(), $request->getParameter('page'), $this->size);
  }

  /**
   * Executes show action
   *
   * @param sfRequest $request A request object
   */
  public function executeShow($request)
  {
    $this->forward404Unless($this->community->isViewableCommunityTopic($this->getUser()->getMemberId()));

    $this->form = new CommunityTopicCommentForm();
  }

  /**
   * Executes new action
   *
   * @param sfRequest $request A request object
   */
  public function executeNew($request)
  {
    $this->forward404Unless($this->community->isCreatableCommunityTopic($this->getUser()->getMemberId()));

    $this->form = new CommunityTopicForm();
  }

  /**
   * Executes create action
   *
   * @param sfRequest $request A request object
   */
  public function executeCreate($request)
  {
    $this->forward404Unless($this->community->isCreatableCommunityTopic($this->getUser()->getMemberId()));

    $this->form = new CommunityTopicForm();
    $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
    $this->form->getObject()->setCommunity($this->community);
    $this->processForm($request, $this->form);

    $this->setTemplate('new');
  }
 
  /**
   * Executes edit action
   *
   * @param sfRequest $request A request object
   */
  public function executeEdit($request)
  {
    $this->forward404Unless($this->communityTopic->isEditable($this->getUser()->getMemberId()));

    $this->form = new CommunityTopicForm($this->communityTopic);
  }
 
  /**
   * Executes update action
   *
   * @param sfRequest $request A request object
   */
  public function executeUpdate($request)
  {
    $this->forward404Unless($this->communityTopic->isEditable($this->getUser()->getMemberId()));

    $this->form = new CommunityTopicForm($this->communityTopic);
    $this->processForm($request, $this->form);

    $this->setTemplate('edit');
  }


  /**
   * Executes deleteConfirm action
   *
   * @param sfRequest $request A request object
   */
  public function executeDeleteConfirm(sfWebRequest $request)
  {
    $this->forward404Unless($this->communityTopic->isEditable($this->getUser()->getMemberId()));

    $this->form = new sfForm();
  }
 
  /**
   * Executes delete action
   *
   * @param sfRequest $request A request object
   */
  public function executeDelete($request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($this->communityTopic->isEditable($this->getUser()->getMemberId()));

    $this->communityTopic->delete();

    $this->getUser()->setFlash('notice', 'The community topic was deleted successfully.');

    $this->redirect('community/home?id='.$this->community->getId());
  }

  protected function processForm($request, sfForm $form)
  {
    $form->bind(
      $request->getParameter($form->getName())
    );

    if ($form->isValid())
    {
      $communityTopic = $form->save();

      $this->redirect('@communityTopic_show?id='.$communityTopic->getId());
    }
  }
}
