<?php
//
//  $Id$
//

ini_set('include_path',realpath(dirname(__FILE__).'/../../../').':'.realpath(dirname(__FILE__).'/../../../../includes').':'.ini_get('include_path'));
require_once 'PHPUnit.php';
require_once 'PHPUnit/GUI/HTML.php';

define('DB_DSN',           'mysql://root@localhost/test');
define('TABLE_TREENESTED', 'TreeNested');

require_once 'Tree/Tree.php';

//
//  run the test suite
//
require_once 'PHPUnit/GUI/SetupDecorator.php';
$gui = new PHPUnit_GUI_SetupDecorator(new PHPUnit_GUI_HTML());
$gui->getSuitesFromDir(dirname(__FILE__),'.*\.php',array('UnitTest.php','index.php','sql.php'));
$gui->show();

//print_r($errors);

?>