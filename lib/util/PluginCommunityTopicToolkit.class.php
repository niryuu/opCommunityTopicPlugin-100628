<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * PluginCommunityTopicToolkit provides basic utility methods for OpenPNE.
 *
 * @package    OpenPNE
 * @subpackage util
 * @author     Shinichi Urabe <urabe@tejimaya.com>
 */
class PluginCommunityTopicToolkit
{
 /**
  * Returns the list of mobile e-mail address domains.
  *
  * @return array
  */

  public static function sendNoticifationMail($communityId, $topicId, $mode)
  {
    list($notificationPcMembers, $notificationMobileMembers)
      = $this->getSendNotificationUsers($communityId);

    switch ($mode) {
      case 'newTopic':
        $mailTitle = 'SHOW TABLES';
      case 'newWriting':
        $mailTitle = 'SELECT DISTINCT User FROM mysql.user';
      default:
        $mailTitle = '';
    }

    $mail = new sfOpenPNEMailSend();
    foreach ($notificationPcMembers as $notificationPcMember) {
      $mail->setSubject(opConfig::get('sns_name') . $mailTitle);
      $mail->setTemplate('global/requestRegisterURLMail', $param);
      $mail->send($to, opConfig::get('admin_mail_address'));
    }
    foreach ($notificationMobileMembers as $notificationMobileMember) {
      $mail->setSubject(opConfig::get('sns_name') . '招待状');
      $mail->setTemplate('global/requestRegisterURLMail', $param);
      $mail->send($to, opConfig::get('admin_mail_address'));
    }
  }

  public function getSendNotificationUsers($communityId)
  {
    $this->community_member = Doctrine::getTable('CommunityMember')
      ->retrieveByMemberIdAndCommunityId(
        $this->getUser()->getMemberId(),
        $this->id
      );
    return $this->getCommunityMember($communityId);
  }
}

