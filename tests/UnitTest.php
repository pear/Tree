<?php
//
//  $Id$
//

require_once 'PHPUnit.php';

class UnitTest extends PhpUnit_TestCase
{
    function setUp()
    {
        
        $this->setLooselyTyped(true);
    }

    function tearDown()
    {
/*        global $dbStructure;

        $querytool = new Common();
        foreach ($dbStructure[$querytool->db->phptype]['tearDown'] as $aQuery) {
//print "$aQuery<br><br>";        
            if (DB::isError($ret=$querytool->db->query($aQuery))) {
                die($ret->getUserInfo());
            }
        }
*/        
    }
    
    function &getMemoryDBnested()
    {
        $tree = Tree::setup('Memory_DBnested',DB_DSN,array('table'=>TABLE_TREENESTED));
        $tree->setup();
        return $tree;
    }
    
    function &getDynamicDBnested()
    {
        $tree = Tree::setup('Dynamic_DBnested',DB_DSN,array('table'=>TABLE_TREENESTED));
        return $tree;
    }
    
}

?>
