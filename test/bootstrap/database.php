<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

$configuration = ProjectConfiguration::getApplicationConfiguration('pc_frontend', 'test', true);
new sfDatabaseManager($configuration);

$task = new sfDoctrineBuildAllReloadTask($configuration->getEventDispatcher(), new sfFormatter());
$task->run(array('--no-confirmation', '--dir='.dirname(__FILE__).'/../fixtures', '--skip-forms'));
