<?php
//  $Id$

require_once 'TreeHelper.php';

class tests_getLevelTest extends TreeHelper
{
    // check if we get the right ID, for the given path
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $this->assertEquals(2, $tree->getLevel($id));
    }

    function test_MemoryMDBnested()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree = $this->getMemoryMDBnested();
        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $this->assertEquals(2, $tree->getLevel($id));
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
    
    // do this for DynamicSQLnested
    function test_DynamicSQLnested()
    {
        $tree =& $this->getDynamicSQLnested();
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $id = 5;
        $this->assertEquals(2, $tree->getLevel($id));
    }
}

?>
