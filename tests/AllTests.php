<?php

// Keep tests from running twice when calling this file directly via PHPUnit.
$call_main = false;
if (strpos($_SERVER['argv'][0], 'phpunit') === false) {
    // Called via php, not PHPUnit.  Pass the request to PHPUnit.
    if (!defined('PHPUnit_MAIN_METHOD')) {
        define('PHPUnit_MAIN_METHOD', 'Tree_AllTests::main');
        $call_main = true;
    }
}

require_once dirname(__FILE__) . '/TreeHelper.php';

class Tree_AllTests
{
    public static function main()
    {
        if (!function_exists('phpunit_autoload')) {
            require_once 'PHPUnit/TextUI/TestRunner.php';
        }
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

	public static function suite()
    {
		$suite = new PHPUnit_Framework_TestSuite('PEAR::Tree Unit Tests');

        $suite->addTestFile(dirname(__FILE__) . '/getElementTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/getIdByPathTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/getLevelTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/getPathTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/moveTest.php');
        $suite->addTestFile(dirname(__FILE__) . '/removeTest.php');

		return $suite;
	}
}

if ($call_main) {
    Tree_AllTests::main();
}
