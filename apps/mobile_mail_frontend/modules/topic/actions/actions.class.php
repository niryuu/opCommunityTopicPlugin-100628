<?php

/**
* This file is part of the OpenPNE package.
* (c) OpenPNE Project (http://www.openpne.jp/)
*
* For the full copyright and license information, please view the LICENSE
* file and the NOTICE file that were distributed with this source code.
*/

/**
* diary actions.
*
* @package OpenPNE
* @subpackage topic
* @author Tajima Itsuro <tajima@tejimaya.com>
*/
class topicActions extends sfActions
{
  public function executeCreate(opMailRequest $request)
  {
    $member = $this->getRoute()->getMember();
    if(!$member)
    {
      return sfView::NONE;
    }

    $community = Doctrine::getTable('Community')->find($request->getParameter('id'));
    if (!$community)
    {
      return sfView::NONE;
    }
    
    $mailMessage = $request->getMailMessage();
    $validator = new opValidatorString(array('rtrim' => true));
    try
    {
      $body = $validator->clean($mailMessage->getContent());
      $title = $validator->clean($mailMessage->getHeader('subject', 'string'));
    }
    catch(Exception $e)
    {
      return sfView::ERROR;
    }
    
    $topic = new CommunityTopic();
    $topic->setMemberId($member);
    $topic->setCommunityId($request->getParameter('id'));
    $topic->setName($title);
    $topic->setBody($body);
    $topic->save();
    return sfView::NONE;
  }
}
