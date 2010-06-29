<?php

class opCommunityTopicPluginImagesListener extends Doctrine_Record_Listener
{
  public function save(Doctrine_Connection $conn = null)
  {
    $this->setFileNamePrefix();

    return parent::save($conn);
  }

  protected function setFileNamePrefix()
  {
    $prefix = '%CLASS%_'.$this->id.'_'.$this->number.'_';

    $file = $this->File;
    $file->setName($prefix.$file->name);
  }

  public function postDelete(Doctrine_Event $event)
  {
    $this->File->FileBin->delete();
    $this->File->delete();
  }
}
