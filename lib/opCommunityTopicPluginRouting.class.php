<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * CommunityTopic routing.
 *
 * @package    OpenPNE
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Rimpei Ogawa <ogawa@tejimaya.com>
 */
class opCommunityTopicPluginRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $routing = $event->getSubject();

    $routes = array(
      'topic' => new opCommunityTopicPluginRouteCollection(array('name' => 'topic')),
      'event' => new opCommunityTopicPluginRouteCollection(array('name' => 'event')),

      'communityEvent_memberList' => new sfDoctrineRoute(
        '/communityEvent/:id/memberList',
        array('module' => 'communityEvent', 'action' => 'memberList'),
        array('id' => '\d+'),
        array('model' => 'CommunityEvent', 'type' => 'object')
      ),

      'communityTopic_recently_topic_list' => new sfRoute(
        '/communityTopic/recentlyTopicList',
        array('module' => 'communityTopic', 'action' => 'recentlyTopicList')
      ),

      'communityEvent_recently_event_list' => new sfRoute(
        '/communityEvent/recentlyEventList',
        array('module' => 'communityEvent', 'action' => 'recentlyEventList')
      ),

      'communityTopic_search' => new sfDoctrineRoute(
        '/communityTopic/search/:id',
        array('module' => 'communityTopic', 'action' => 'search'),
        array('id' => '\d+'),
        array('model' => 'CommunityTopic', 'type' => 'object')
      ),

      'communityTopic_search_all' => new sfRoute(
        '/communityTopic/search',
        array('module' => 'communityTopic', 'action' => 'search')
      ),

      'communityTopic_search_form' => new sfRoute(
        '/communityTopic/searchForm',
        array('module' => 'communityTopic', 'action' => 'searchForm')
      ),

      'communityTopic_nodefaults' => new sfRoute(
        '/communityTopic/*',
        array('module' => 'default', 'action' => 'error')
      ),
    );

    $routes = array_reverse($routes);
    foreach ($routes as $name => $route)
    {
      $routing->prependRoute($name, $route);
    }
  }
}
