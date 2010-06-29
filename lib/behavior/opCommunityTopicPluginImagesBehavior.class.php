<?php

class opCommunityTopicPluginImagesBehavior extends Doctrine_Template
{
  public function __construct(array $options = array())
  {
    parent::__construct($options);

    $this->_plugin = new opCommunityTopicPluginImagesRecordGenerator($this->_options);
  }

  public function setUp()
  {
    error_log(var_dump($this));
    $this->_plugin->initialize($this->_table);
    $this->addListener(new opCommunityTopicPluginImagesListener($this->_options));
  }
}
