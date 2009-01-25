<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * communityTopicComment actions.
 *
 * @package    OpenPNE
 * @subpackage communityTopicComment
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class communityTopicCommentActions extends sfActions
{
 /**
  * Executes create action
  *
  * @param sfRequest $request A request object
  */
  public function executeCreate(sfWebRequest $request)
  {
    $this->communityTopic = $this->getRoute()->getObject();
    $this->community = $this->communityTopic->getCommunity();

    if ($this->community->getConfig('public_flag') === 'auth_commu_member')
    {
      $this->forward404Unless($this->community->isPrivilegeBelong($this->getUser()->getMemberId()));
    }

    $this->form = new CommunityTopicCommentForm();
    $this->form->getObject()->setMemberId($this->getUser()->getMemberId());
    $this->form->getObject()->setCommunityTopic($this->communityTopic);
    $this->form->bind($request->getParameter($this->form->getName()));
    if ($this->form->isValid())
    {
      $this->form->save();
      $this->redirect($this->generateUrl('communityTopic_show', $this->communityTopic));
    }

    $this->setTemplate('../../communityTopic/templates/show');
  }
}
