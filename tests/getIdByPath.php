<?php
//
//  $Id$
//

require_once 'UnitTest.php';

class tests_getIdByPath extends UnitTest
{
    // check if we get the right ID, for the given path
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        
        $this->assertEquals(5,$id);
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
    
    // do this for DynamicDBnested
    function test_DynamicDBnested()
    {
        $tree = $this->getDynamicDBnested();
        $id = $tree->getIdByPath('/Root/child 2/child 2_2');

        $this->assertEquals(5,$id);
    }
    


}

?>
