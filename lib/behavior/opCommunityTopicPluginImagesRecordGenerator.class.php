<?php

class opCommunityTopicPluginImagesRecordGenerator extends Doctrine_Record_Generator
{
  protected $_options = array(
    'className'     => '%CLASS%Image',
    'tableName'     => '%TABLE%_image',
    'generateFiles' => true,
    'table'         => false,
    'pluginTable'   => false,
    'children'      => array(),
    'options'       => array(),
    'cascadeDelete' => true,
    'appLevelDelete'=> false,
  );

  public function __construct($options)
  {
    $builderOptions = array(
      'suffix' =>  '.class.php',
      'baseClassesDirectory' => 'base',
      'generateBaseClasses' => true,
      'generateTableClasses' => true,
      'baseClassName' => 'opDoctrineRecord'
    );
    $this->setOption('generatePath', sfConfig::get('sf_lib_dir') . '/model/doctrine/opCommunityTopicPlugin');
    $this->setOption('builderOptions', $builderOptions);
    $this->_options = Doctrine_Lib::arrayDeepMerge($this->_options, $options);
  }

  public function buildRelation()
  {
    $this->buildForeignRelation('Images');
    $this->buildLocalRelation();
  }

  public function setTableDefinition()
  {
    $this->hasColumn('file_id', 'integer', 4, array(
      'type' => 'integer',
      'notnull' => true,
      'length' => 4,
    ));

    $this->hasColumn('number', 'integer', 4, array(
      'type' => 'integer',
      'notnull' => true,
      'length' => 4,
    ));

    $this->hasColumn('post_id', 'integer', 4, array(
      'type' => 'integer',
      'notnull' =>true,
      'length' => 4,
    ));

    $this->index('id_number', array(
      'fields' => 
       array(
         0 => 'id',
         1 => 'number',
       ),
       'type' => 'unique',
     ));
    $this->option('charset', 'utf8');
  }

  public function setUp()
  {
    parent::setUp();

    $this->hasOne('File', array(
      'local' => 'file_id',
      'foreign' => 'id',
      'onDelete' => 'cascade',
    ));
  }
}
