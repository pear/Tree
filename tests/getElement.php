<?php
//
//  $Id$
//

require_once 'UnitTest.php';

class tests_getElement extends UnitTest
{
    /**
    *   There was a bug when we mapped column names, especially when we mapped 
    *   a column to the same name as the column. We check this here too.
    *
    *
    */
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $tree->update(3,array('comment'=>'PEAR rulez'));
        $tree->setup();
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['comment']);

        $tree->setOption('columnNameMaps',array('comment'=>'comment'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['comment']);
    
        $tree->setOption('columnNameMaps',array('myComment'=>'comment'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['myComment']);
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
    
    // do this for DynamicDBnested
    function test_DynamicDBnested()
    {
        $tree =& $this->getDynamicDBnested();
        $tree->update(3,array('comment'=>'PEAR rulez'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['comment']);

        $tree->setOption('columnNameMaps',array('comment'=>'comment'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['comment']);
    
        $tree->setOption('columnNameMaps',array('myComment'=>'comment'));
        $actual = $tree->getElement(3);
        $this->assertEquals('PEAR rulez',$actual['myComment']);
    }
    
}

?>
