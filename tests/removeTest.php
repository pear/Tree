<?php
//  $Id$

require_once 'TreeHelper.php';

class tests_removeTest extends TreeHelper
{
/*
    function test_MemoryDBnested()
    {
        $tree = $this->getMemoryDBnested();        
        $ret=$tree->remove(5);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        // and check if the move succeeded, by checking the new parentId
        //problem here is that memory returns another return value for a not existing element ... shit        
        $this->assertEquals(x, $tree->getElement(5));
    }
*/

    function test_MemoryDBnestedNoAction()
    {
        $tree = $this->getMemoryDBnested();        
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $parent = $tree->getParent(5);
        $ret = $tree->move(5, 5);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        $parent1 = $tree->getParent(5);
        $this->assertEquals($parent['id'], $parent1['id']);
    }

    function test_MemoryMDBnestedNoAction()
    {
        if (!$this->has_mdb) {
            $this->markTestSkipped('MDB is not installed');
        }

        $tree = $this->getMemoryMDBnested();        
//        $id = $tree->getIdByPath('/Root/child 2/child 2_2');
        $parent = $tree->getParent(5);
        $ret = $tree->move(5, 5);
        $tree->setup();

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        $parent1 = $tree->getParent(5);
        $this->assertEquals($parent['id'], $parent1['id']);
    }

    // do this for XML
            
    // do this for Filesystem

    // do this for DBsimple
  
    // do this for DynamicSQLnested
    function test_DynamicSQLnested()
    {
        $tree =& $this->getDynamicSQLnested();
        $ret = $tree->remove(5);

        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }

        // and check if the element doesnt exist anymore ... this is not 100% sure, since the 
        // returned error message is a string :-(
        // Avoid PHPUnit exhausting memory if $ret is a large array or object.
        $ret = Tree::isError($tree->getElement(5));
        if (is_bool($ret)) {
            $this->assertTrue($ret);
        } else {
            $this->fail('Expected TRUE but got a ' . gettype($ret));
        }
    }
}

?>
